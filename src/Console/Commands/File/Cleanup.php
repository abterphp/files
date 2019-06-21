<?php

declare(strict_types=1);

namespace AbterPhp\Files\Console\Commands\File;

use AbterPhp\Files\Domain\Entities\File as Entity;
use AbterPhp\Files\Orm\FileRepo;
use AbterPhp\Framework\Filesystem\Uploader;
use FilesystemIterator;
use Opulence\Console\Commands\Command;
use Opulence\Console\Requests\Option;
use Opulence\Console\Requests\OptionTypes;
use Opulence\Console\Responses\IResponse;
use Opulence\Orm\IUnitOfWork;

class Cleanup extends Command
{
    const COMMAND_NAME            = 'files:cleanup';
    const COMMAND_DESCRIPTION     = 'Cleanup missing files both from database and filesystem';
    const COMMAND_SUCCESS         = '<success>Files are cleaned up.</success>';
    const COMMAND_DRY_RUN_MESSAGE = '<info>Dry run prevented deleting files and database rows.</info>';

    const OPTION_DRY_RUN    = 'dry-run';
    const SHORTENED_DRY_RUN = 'd';

    const DELETING_FILES    = 'Deleting files missing from database:';
    const DELETING_ENTITIES = 'Deleting database entities missing from filesystem:';

    const FS_ONLY = 'Files to delete: %d';
    const DB_ONLY = 'Database entities to delete: %d';

    /** @var FileRepo */
    protected $fileRepo;

    /** @var IUnitOfWork */
    protected $unitOfWork;

    /** @var Uploader */
    protected $uploader;

    /** @var array */
    protected $filesToSkip = ['.gitignore', '.gitkeep'];

    /**
     * Cleanup constructor.
     *
     * @param FileRepo    $fileRepo
     * @param IUnitOfWork $unitOfWork
     * @param Uploader    $uploader
     */
    public function __construct(
        FileRepo $fileRepo,
        IUnitOfWork $unitOfWork,
        Uploader $uploader
    ) {
        $this->fileRepo   = $fileRepo;
        $this->unitOfWork = $unitOfWork;
        $this->uploader   = $uploader;

        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    protected function define()
    {
        $this->setName(static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION)
            ->addOption(
                new Option(
                    static::OPTION_DRY_RUN,
                    static::SHORTENED_DRY_RUN,
                    OptionTypes::OPTIONAL_VALUE,
                    'Dry run (default: 0)',
                    '0'
                )
            );
    }

    /**
     * @inheritdoc
     */
    protected function doExecute(IResponse $response)
    {
        $dbPaths = $this->getDatabasePaths($response);
        $fsPaths = $this->getFilesystemPaths();

        $fsOnly = $this->filterFilesystemOnly($response, $dbPaths, $fsPaths);
        $dbOnly = $this->filterDatabaseOnly($response, $dbPaths, $fsPaths);

        $dryRun = $this->isDryRun($response);
        if ($dryRun) {
            return;
        }

        $this->deleteFilesFromDatabase($response, $dbOnly);
        $this->deleteFilesFromFilesystem($response, $fsOnly);

        $this->commit($response);
    }

    /**
     * @param IResponse $response
     *
     * @return string[]
     */
    protected function getDatabasePaths(IResponse $response): array
    {
        try {
            /** @var Entity[] $entities */
            $entities = $this->fileRepo->getAll();
        } catch (\Exception $e) {
            if ($e->getPrevious()) {
                $response->writeln(sprintf('<error>%s</error>', $e->getPrevious()->getMessage()));
            }
            $response->writeln(sprintf('<fatal>%s</fatal>', $e->getMessage()));

            return [];
        }

        $paths = [];
        foreach ($entities as $entity) {
            $paths[$entity->getId()] = $this->uploader->getPath(
                Uploader::DEFAULT_KEY,
                $entity->getFilesystemName()
            );
        }

        return $paths;
    }

    /**
     * @return string[]
     */
    protected function getFilesystemPaths(): array
    {
        $path = $this->uploader->getPath(Uploader::DEFAULT_KEY);

        $iterator = new FilesystemIterator($path);

        $paths = [];
        /** @var \SplFileInfo $fileInfo */
        foreach ($iterator as $fileInfo) {
            if (!$fileInfo->isFile()) {
                continue;
            }
            $filename = $fileInfo->getFilename();
            if (in_array($filename, $this->filesToSkip)) {
                continue;
            }

            $paths[] = $fileInfo->getRealPath();
        }

        return $paths;
    }

    /**
     * @param IResponse $response
     * @param string[]  $dbPaths
     * @param string[]  $fsPaths
     *
     * @return string[]
     */
    protected function filterFilesystemOnly(IResponse $response, array $dbPaths, array $fsPaths): array
    {
        $dbPaths = array_flip($dbPaths);

        $filteredPaths = [];
        foreach ($fsPaths as $fsPath) {
            if (array_key_exists($fsPath, $dbPaths)) {
                continue;
            }

            $filteredPaths[] = $fsPath;
        }

        $response->writeln(sprintf('<info>%s</info>', sprintf(static::FS_ONLY, count($filteredPaths))));

        return $filteredPaths;
    }

    /**
     * @param IResponse $response
     * @param string[]  $dbPaths
     * @param string[]  $fsPaths
     *
     * @return string[]
     */
    protected function filterDatabaseOnly(IResponse $response, array $dbPaths, array $fsPaths): array
    {
        $fsPaths = array_flip($fsPaths);

        $filteredPaths = [];
        foreach ($dbPaths as $id => $dbPath) {
            if (array_key_exists($dbPath, $fsPaths)) {
                continue;
            }

            $filteredPaths[$id] = $dbPath;
        }

        $response->writeln(sprintf('<info>%s</info>', sprintf(static::DB_ONLY, count($filteredPaths))));

        return $filteredPaths;
    }

    /**
     * @param IResponse $response
     *
     * @return bool
     */
    protected function isDryRun(IResponse $response): bool
    {
        $dryRun = (bool)$this->getOptionValue(static::OPTION_DRY_RUN);
        if (!$dryRun) {
            return $dryRun;
        }

        $this->unitOfWork->dispose();
        $response->writeln(static::COMMAND_DRY_RUN_MESSAGE);

        return $dryRun;
    }

    /**
     * @param IResponse $response
     * @param string[]  $fsOnly
     */
    protected function deleteFilesFromDatabase(IResponse $response, array $fsOnly)
    {
        if (count($fsOnly) > 0) {
            $response->writeln(sprintf('<info>%s</info>', static::DELETING_ENTITIES));
        }

        foreach ($fsOnly as $id => $path) {
            try {
                $this->fileRepo->delete(new Entity($id, '', '', '', ''));
                $response->writeln(sprintf('<comment>%d: %s</comment>', $id, $path));
            } catch (\Exception $e) {
                if ($e->getPrevious()) {
                    $response->writeln(sprintf('<error>%s</error>', $e->getPrevious()->getMessage()));
                }
                $response->writeln(sprintf('<fatal>%s</fatal>', $e->getMessage()));
            }
        }
    }

    /**
     * @param IResponse $response
     * @param string[]  $dbOnly
     */
    protected function deleteFilesFromFilesystem(IResponse $response, array $dbOnly)
    {
        if (count($dbOnly) > 0) {
            $response->writeln(sprintf('<info>%s</info>', static::DELETING_FILES));
        }

        foreach ($dbOnly as $path) {
            try {
                @unlink($path);
                $response->writeln(sprintf('<comment>%s</comment>', $path));
            } catch (\Exception $e) {
                if ($e->getPrevious()) {
                    $response->writeln(sprintf('<error>%s</error>', $e->getPrevious()->getMessage()));
                }
                $response->writeln(sprintf('<fatal>%s</fatal>', $e->getMessage()));
            }
        }
    }

    /**
     * @param IResponse $response
     */
    protected function commit(IResponse $response)
    {
        try {
            $this->unitOfWork->commit();
            $response->writeln(static::COMMAND_SUCCESS);
        } catch (\Exception $e) {
            if ($e->getPrevious()) {
                $response->writeln(sprintf('<error>%s</error>', $e->getPrevious()->getMessage()));
            }
            $response->writeln(sprintf('<fatal>%s</fatal>', $e->getMessage()));
        }
    }
}
