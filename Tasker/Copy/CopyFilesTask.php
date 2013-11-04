<?php
/**
 * Class CopyFilesTask
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 28.08.13
 */
namespace Tasker\Copy;

use Tasker\ErrorException;
use Tasker\Tasks\Task;
use Tasker\Utils\FileSystem;

class CopyFilesTask extends Task
{

	/**
	 * @param array $config
	 * @return array
	 * @throws \Tasker\ErrorException
	 */
	public function run($config)
	{
		$results = array();
		if(count($config)) {
			foreach ($config as $dest => $sources) {
				if(!is_string($dest)) {
					throw new ErrorException('Destination must be valid path');
				}

				if(is_string($sources)) {
					$sources = array($sources);
				}

				if(count($sources)) {
					foreach ($sources as $source) {
						FileSystem::cp($this->getFullPath($dest), $this->getFullPath($source));
						$results[] = 'Files from folder "' . $source . '" was copied to "' . $dest . '"';
					}
				}
			}
		}

		return $results;
	}

	/**
	 * @param $path
	 * @return string
	 */
	protected function getFullPath($path)
	{
		return $this->setting->getRootPath() . DIRECTORY_SEPARATOR . $path;
	}
}