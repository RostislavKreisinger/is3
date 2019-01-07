<?php

namespace App\Exceptions;


/**
 * Class ProjectUserMissingException
 * @package App\Exceptions
 */
class ProjectUserMissingException extends \Exception {
    /**
     * @var int $projectId
     */
    private $projectId;

    /**
     * @var int $userId
     */
    private $userId;

    /**
     * ProjectUserMissingException constructor.
     * @param int $projectId
     * @param int $userId
     */
    public function __construct(int $projectId, int $userId) {
        $this->setProjectId($projectId);
        $this->setUserId($userId);
        parent::__construct("Project {$this->getProjectId()} cannot be properly loaded because user {$this->getUserId()} doesn't exist.", 404);
    }

    /**
     * @return int
     */
    public function getProjectId(): int {
        return $this->projectId;
    }

    /**
     * @param int $projectId
     * @return ProjectUserMissingException
     */
    public function setProjectId(int $projectId): ProjectUserMissingException {
        $this->projectId = $projectId;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserId(): int {
        return $this->userId;
    }

    /**
     * @param int $userId
     * @return ProjectUserMissingException
     */
    public function setUserId(int $userId): ProjectUserMissingException {
        $this->userId = $userId;
        return $this;
    }
}