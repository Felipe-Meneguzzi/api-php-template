<?php
declare(strict_types=1);

namespace App\Module\RequestLog\Service;

use App\Entity\RequestLogEntity;
use App\Module\RequestLog\DTO\Input\RequestLogIDTO;
use App\Module\RequestLog\Repository\IRequestLogRepository;
use DateTime;

interface IRequestLogService {
	public function Run(RequestLogIDTO $iDTO): bool;
}

class RequestLogService implements IRequestLogService {
	public function __construct(protected IRequestLogRepository $repository) {}

	public function Run(RequestLogIDTO $iDTO): bool {
        $formatedTime = DateTime::createFromFormat('d/m/Y H:i:s', $iDTO->time);

        $data = [
            'user_id' => $iDTO->user_id,
            'uri' => $iDTO->uri,
            'method' => $iDTO->method,
            'headers' => json_encode($iDTO->headers),
            'body' => json_encode($iDTO->body),
            'cookies' => json_encode($iDTO->cookies),
            'agent' => $iDTO->agent,
            'time' => $formatedTime,
            'ip' => $iDTO->ip
        ];

        $entity = new RequestLogEntity($data);

		return $this->repository->insert($entity);
	}
}