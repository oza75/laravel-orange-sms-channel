<?php


namespace Oza75\OrangeSMSChannel;


class OrangeMessage
{
    /**
     * @var string
     */
    protected string $to;

    /**
     * @var string
     */
    protected $from;

    /**
     * @var string|null
     */
    protected $senderName;

    /**
     * @var string
     */
    protected string $text;

    /**
     * @param $to
     * @return $this
     */
    public function to($to): OrangeMessage
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @param string $from
     * @param string|null $name
     * @return $this
     */
    public function from(string $from, ?string $name = null): OrangeMessage
    {
        $this->from = $from;
        $this->senderName = $name;

        return $this;
    }

    /**
     * @param string $text
     * @return $this
     */
    public function text(string $text = ''): OrangeMessage
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @return string
     */
    public function getFrom(): ?string
    {
        return $this->from;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return string|null
     */
    public function getSenderName(): ?string
    {
        return $this->senderName;
    }
}