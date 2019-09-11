<?php
declare(strict_types=1);

namespace App\External\Libraries\Doctrine\Paginator;

use App\External\Interfaces\PaginatorInterface;
use EoneoPay\Utils\XmlConverter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;

class PaginatorWrapper implements PaginatorInterface
{
    /**
     * @var \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    private $paginator;

    /**
     * PaginatorWrapper constructor.
     *
     * @param \Illuminate\Contracts\Pagination\LengthAwarePaginator $paginator
     */
    public function __construct(LengthAwarePaginatorContract $paginator)
    {
        $this->paginator = $paginator;
    }

    /**
     * Get current page.
     *
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->paginator->currentPage();
    }

    /**
     * Get current items being paginated.
     *
     * @return mixed[]
     */
    public function getItems(): array
    {
        return $this->paginator->items();
    }

    /**
     * Get items to be shown per page.
     *
     * @return int
     */
    public function getItemsPerPage(): int
    {
        return $this->paginator->perPage();
    }

    /**
     * Get total number of paginated items.
     *
     * @return int
     */
    public function getTotalItems(): int
    {
        return $this->paginator->total();
    }

    /**
     * Get total number of pages based on the total number of items.
     *
     * @return int
     */
    public function getTotalPages(): int
    {
        return $this->paginator->lastPage();
    }

    /**
     * When current page has a next page.
     *
     * @return bool
     */
    public function hasNextPage(): bool
    {
        return (bool)$this->paginator->nextPageUrl();
    }

    /**
     * When current page has a previous page.
     *
     * @return bool
     */
    public function hasPreviousPage(): bool
    {
        return (bool)$this->paginator->previousPageUrl();
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @return mixed[]
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Get the contents of the repository as an array.
     *
     * @return mixed[]
     */
    public function toArray(): array
    {
        return [
            'items' => $this->getItems(),
            'pagination' => [
                'current_page' => $this->getCurrentPage(),
                'has_next_page' => $this->hasNextPage(),
                'has_previous_page' => $this->hasPreviousPage(),
                'items_per_page' => $this->getItemsPerPage(),
                'total_items' => $this->getTotalItems(),
                'total_pages' => $this->getTotalPages()
            ]
        ];
    }

    /**
     * Generate json from the repository.
     *
     * @return string
     */
    public function toJson(): string
    {
        return (string)\json_encode($this->jsonSerialize());
    }

    /**
     * Generate XML string from the repository.
     *
     * @param null|string $rootNode
     *
     * @return string
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidXmlTagException
     */
    public function toXml(?string $rootNode = null): string
    {
        return (new XmlConverter())->arrayToXml($this->toArray(), $rootNode);
    }
}
