<?php

namespace App\Helpers\API;


/**
 * Class ISAPIRequest
 * @package App\Helpers\API
 */
class ISAPIRequest {
    /**
     * @var string $endpoint
     */
    private $endpoint = '';

    /**
     * @var array $fields
     */
    private $fields = [];

    /**
     * @var array $filters
     */
    private $filters = [];

    /**
     * @var array $includes
     */
    private $includes = [];

    /**
     * @var array $page
     */
    private $page = [];

    /**
     * @var array $sorts
     */
    private $sorts = [];

    /**
     * ISAPIRequest constructor.
     * @param string $endpoint
     * @param array $fields
     * @param array $filters
     * @param array $includes
     * @param array $page
     * @param array $sorts
     */
    public function __construct(string $endpoint, array $fields = [], array $filters = [], array $includes = [], array $page = ['number' => 1, 'size' => 15], array $sorts = []) {
        $this->setEndpoint($endpoint);
        $this->setFields($fields);
        $this->setFilters($filters);
        $this->setIncludes($includes);
        $this->setPage($page);
        $this->setSorts($sorts);
    }

    /**
     * @return string
     */
    public function getEndpoint(): string {
        return $this->endpoint;
    }

    /**
     * @param string $endpoint
     * @return ISAPIRequest
     */
    public function setEndpoint(string $endpoint): ISAPIRequest {
        $this->endpoint = $endpoint;
        return $this;
    }

    /**
     * @return array
     */
    public function getFields(): array {
        return $this->fields;
    }

    /**
     * @param array $fields
     * @return ISAPIRequest
     */
    public function setFields(array $fields): ISAPIRequest {
        $this->fields = $fields;
        return $this;
    }

    /**
     * @return array
     */
    public function getFilters(): array {
        return $this->filters;
    }

    /**
     * @param array $filters
     * @return ISAPIRequest
     */
    public function setFilters(array $filters): ISAPIRequest {
        $this->filters = $filters;
        return $this;
    }

    /**
     * @return array
     */
    public function getIncludes(): array {
        return $this->includes;
    }

    /**
     * @param array $includes
     * @return ISAPIRequest
     */
    public function setIncludes(array $includes): ISAPIRequest {
        $this->includes = $includes;
        return $this;
    }

    /**
     * @return array
     */
    public function getPage(): array {
        return $this->page;
    }

    /**
     * @param array $page
     * @return ISAPIRequest
     */
    public function setPage(array $page): ISAPIRequest {
        $this->page = $page;
        return $this;
    }

    /**
     * @return array
     */
    public function getSorts(): array {
        return $this->sorts;
    }

    /**
     * @param array $sorts
     * @return ISAPIRequest
     */
    public function setSorts(array $sorts): ISAPIRequest {
        $this->sorts = $sorts;
        return $this;
    }
}