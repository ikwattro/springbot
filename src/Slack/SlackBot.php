<?php

namespace Ikwattro\SpringBot\Slack;

use Maknz\Slack\Attachment;
use Maknz\Slack\Client;

class SlackBot
{
    private $client;

    public function __construct(array $config)
    {
        $settings = [
            'username' => (string) $config['username'],
            'channel' => (string) $config['channel'],
            'link_names' => true
        ];

        $this->client = new Client($config['hook_url'], $settings);
    }

    public function postMessage($question)
    {
        $attachement = [
            'fallback' => 'New SDN4 question on SO - ' . $question['title'] . ' - ' . $question['link'],
            'pretext' => 'New question from ' . $question['owner']['display_name'],
            'title' => $question['title'],
            'title_link' => $question['link'],
            'color' => '#7CD197'
        ];

        print_r($attachement);

        $attach = new Attachment($attachement);
        $message = $this->client->createMessage();
        $message->attach($attach);

        return $message->setText('New SDN4 question')->send();
    }
}