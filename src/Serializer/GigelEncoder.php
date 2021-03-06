<?php

declare(strict_types=1);

namespace App\Serializer;

use Symfony\Component\Serializer\Encoder\EncoderInterface;

class GigelEncoder implements EncoderInterface
{
    public function encode($data, string $format, array $context = []): string
    {
        $transformed = array_map(
            function () {
                return ['greeting' => 'Salutare sunt Gigel!'];
            },
            $data
        );
        return \json_encode($transformed);
    }

    public function supportsEncoding(string $format): bool
    {
        return 'gigel' === $format;
    }
}
