<?php

namespace Knevelina\Prolite\Components;

use InvalidArgumentException;
use Knevelina\Prolite\Display;
use Knevelina\Prolite\DisplayText;

class PageBank
{
    /**
     * The maximum size of one page, in bytes.
     */
    const PAGE_SIZE = 1000;

    /**
     * @var int The display ID this page bank belongs to.
     */
    private $displayId;

    /**
     * @var array The pages in this page bank.
     */
    private $pages;

    /**
     * Construct a new PageBank.
     *
     * @param int $displayId
     */
    public function __construct(int $displayId)
    {
        Display::verifyDisplayId($displayId);

        $this->displayId = $displayId;
        $this->pages = [];

        foreach (range('A', 'Z') as $id) {
            $this->pages[$id] = '';
        }
    }

    /**
     * Query whether the page bank has a page for a given index.
     *
     * @param int $page
     * @return bool
     */
    public function hasPage(int $page): bool
    {
        return strlen($this->pages[self::getPageID($page)]) > 0;
    }

    /**
     * Get the page ID for a given index.
     *
     * @param int $index
     * @return string
     */
    private static function getPageID(int $index): string
    {
        if ($index < 0 || $index > 25) {
            throw new InvalidArgumentException(sprintf('%d is not a valid page.', $index));
        }

        return chr(65 + $index);
    }

    /**
     * Get the page contents for a given index.
     *
     * @param int $index
     * @return mixed
     */
    public function getPage(int $index)
    {
        return $this->pages[self::getPageID($index)];
    }

    /**
     * Set the page contents for a given index.
     *
     * @param int $page
     * @param string $content
     */
    public function setPage(int $page, string $content)
    {
        $id = self::getPageID($page);
        $content = DisplayText::normalize($content);

        if (strlen($content) > self::PAGE_SIZE) {
            throw new InvalidArgumentException(
                sprintf('Page content for page %s is too long (maximum %d characters)', $id, self::PAGE_SIZE)
            );
        }

        $this->pages[$id] = $content;
    }

    /**
     * Get the configuration line(s) needed to define the pages in this page bank.
     *
     * @return string
     */
    public function getConfiguration(): string
    {
        $header = sprintf('<ID%02X>', $this->displayId);
        $eol = "\r\n";
        $config = '';

        foreach ($this->pages as $id => $content) {
            if (strlen($content)) {
                $config .= sprintf('%s<P%s>%s', $header, $id, $content) . $eol;
            }
        }

        return $config;
    }

    /**
     * Get the display ID this graphics bank belongs to.
     *
     * @return int
     */
    public function getDisplayId(): int
    {
        return $this->displayId;
    }
}