<?php

namespace Ikwattro\SpringBot\Database;

use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

class JsonDB
{
    private $location;

    private $fs;

    public function __construct($location)
    {
        $this->location = $location;
        $this->fs = new Filesystem();
        $this->initialize();
    }

    public function hasRecord($recordId)
    {
        return $this->fs->exists($this->formatRecordId($recordId));
    }

    public function persistRecord($recordId, $record)
    {
        if (is_array($record)) {
            $record = json_encode($record);
        }

        try {
            $this->fs->dumpFile($this->formatRecordId($recordId), $record);
        } catch (IOException $e) {
            throw new IOException("Unable to write record on disk");
        }

    }

    public function formatRecordId($recordId)
    {
        if (null == $recordId || "" == $recordId) {
            throw new \InvalidArgumentException("The given record ID is invalid");
        }

        return $this->location . DIRECTORY_SEPARATOR . $recordId . ".json";
    }

    private function initialize()
    {
        if (!$this->fs->exists($this->location)) {
            try {
                $this->fs->mkdir($this->location);
            } catch (IOException $e) {
                throw $e;
            }
        }
    }

}