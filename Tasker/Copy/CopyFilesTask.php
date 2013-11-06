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
					$dest = $this->getFullPath($dest);

					foreach ($sources as $source) {
						$source = $this->getFullPath($source);

						if(!file_exists($source)) {
							$results[] = 'File ' . $source . ' cannot be copied. File does not exist.';
						}else{

							if(!is_dir($source)) {
								$fileName = explode(DIRECTORY_SEPARATOR, $source);
								$fileName = $fileName[count($fileName) - 1];
								$dest .= DIRECTORY_SEPARATOR . $fileName;;
								FileSystem::write($dest, FileSystem::read($source));
							}else{
								FileSystem::cp($source, $dest);
							}

							$results[] = 'Files from folder "' . $source . '" was copied to "' . $dest . '"';
						}
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