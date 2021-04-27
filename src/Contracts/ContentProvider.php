<?php

namespace Tekord\RobotsTxtProvider\Contracts;

/**
 * @author Cyrill Tekord
 */
interface ContentProvider {
	public function getContent(): string;
}
