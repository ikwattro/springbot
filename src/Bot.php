<?php

namespace Ikwattro\SpringBot;

use Ikwattro\SpringBot\Database\JsonDB;
use Ikwattro\SpringBot\Slack\SlackBot;
use Ikwattro\SpringBot\SO\StackOverflowClient;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Yaml;

class Bot
{
    private $db;

    private $stackOverflowClient;

    private $slackBot;

    public function __construct()
    {
        $configRaw = file_get_contents(__DIR__.'/../config/config.yml');
        $config = Yaml::parse($configRaw);
        $this->db = new JsonDB(__DIR__.'/../data');
        $this->stackOverflowClient = new StackOverflowClient($config['tags']);
        $this->slackBot = new SlackBot($config['slack']);
    }

    public function process()
    {
        $questions = $this->stackOverflowClient->getQuestions();

        foreach ($questions['items'] as $question) {
            if (!$this->db->hasRecord($question['question_id'])) {
                $this->db->persistRecord($question['question_id'], $question);
                $this->slackBot->postMessage($question);
            }
        }
    }
}