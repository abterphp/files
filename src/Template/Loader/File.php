<?php

declare(strict_types=1);

namespace AbterPhp\Files\Template\Loader;

use AbterPhp\Files\Constant\Routes;
use AbterPhp\Framework\Html\Helper\StringHelper;
use AbterPhp\Framework\Template\IData;
use AbterPhp\Framework\Template\ILoader;
use AbterPhp\Framework\Template\Data;
use AbterPhp\Files\Domain\Entities\File as Entity;
use AbterPhp\Files\Orm\FileRepo;
use AbterPhp\Framework\Template\ParsedTemplate;
use Opulence\Routing\Urls\UrlGenerator;

class File implements ILoader
{
    const TAG_A  = 'a';
    const TAG_LI = 'li';
    const TAG_UL = 'ul';

    const ATTRIBUTE_CLASS = 'class';
    const ATTRIBUTE_HREF  = 'href';

    const FILE_CATEGORY_CLASS = 'file-category';

    /** @var FileRepo */
    protected $fileRepo;

    /** @var UrlGenerator */
    protected $urlGenerator;

    /**
     * File constructor.
     *
     * @param FileRepo     $fileRepo
     * @param UrlGenerator $urlGenerator
     */
    public function __construct(FileRepo $fileRepo, UrlGenerator $urlGenerator)
    {
        $this->fileRepo     = $fileRepo;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param ParsedTemplate[] $parsedTemplates
     *
     * @return IData[]
     */
    public function load(array $parsedTemplates): array
    {
        $identifiers = array_keys($parsedTemplates);

        $files = $this->fileRepo->getPublicByCategoryIdentifiers($identifiers);

        $filesByCategories = $this->getFilesByCategory($files);

        return $this->getTemplateData($filesByCategories);
    }

    /**
     * @param Entity[] $files
     *
     * @return Entity[][]
     */
    protected function getFilesByCategory(array $files): array
    {
        $return = [];
        foreach ($files as $file) {
            $return[$file->getCategory()->getIdentifier()][] = $file;
        }

        return $return;
    }

    /**
     * @param Entity[][] $files
     *
     * @return IData[]
     */
    protected function getTemplateData(array $files): array
    {
        $templateData = [];
        foreach ($files as $categoryIdentifier => $categoryFiles) {
            $templateData[] = new Data(
                $categoryIdentifier,
                [],
                ['body' => $this->getCategoryHtml($categoryFiles, $categoryIdentifier)]
            );
        }

        return $templateData;
    }

    /**
     * @param Entity[] $files
     * @param string   $categoryIdentifier
     *
     * @return string
     */
    protected function getCategoryHtml(array $files, string $categoryIdentifier): string
    {
        $html = [];
        foreach ($files as $file) {
            $url  = $this->urlGenerator->createFromName(Routes::ROUTE_PUBLIC_FILE, $file->getFilesystemName());
            $link = StringHelper::wrapInTag(
                $file->getPublicName(),
                static::TAG_A,
                [
                    static::ATTRIBUTE_HREF => $url,
                ]
            );

            $html[] = StringHelper::wrapInTag($link, static::TAG_LI);
        }

        return StringHelper::wrapInTag(
            implode('', $html),
            static::TAG_UL,
            [
                static::ATTRIBUTE_CLASS => [static::FILE_CATEGORY_CLASS, $categoryIdentifier],
            ]
        );
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param string[] $identifiers
     * @param string   $cacheTime
     *
     * @return bool
     */
    public function hasAnyChangedSince(array $identifiers, string $cacheTime): bool
    {
        return true;
    }
}
