<?php
namespace mohdradzee\WatiNotification;

class WatiMessage
{
    public string $type = 'send_template'; // default action
    public string $phone;
    public string $template;
    public string $broadcast_name;
    public array $parameters = [];
    public array $meta = [];

    public static function create(): self
    {
        return new static();
    }

    public function to(string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    public function usingTemplate(string $template): self
    {
        $this->template = $template;
        return $this;
    }

    public function withBroadcastName(string $broadcast_name): self
    {
        $this->broadcast_name = $broadcast_name;
        return $this;
    }

    public function withParameters(array $parameters): self
    {
        $this->parameters = $parameters;
        return $this;
    }

    public function withAction(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function withMeta(array $meta): self
    {
        $this->meta = $meta;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'phone' => $this->phone,
            'template_name' => $this->template,
            'broadcast_name' => $this->broadcast_name,
            'parameters' => $this->parameters,
            'meta' => $this->meta,
            'type' => $this->type,
        ];
    }
}

?>
