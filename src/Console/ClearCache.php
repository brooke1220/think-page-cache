<?php

namespace Brooke\HttpCache\Console;

use Brooke\HttpCache\Cache;
use think\console\Input;
use think\console\Output;
use think\console\Command;
use think\console\input\Argument;

class ClearCache extends Command
{
    protected function configure()
    {
        $this->setName('http-cache:clear')
          ->setDescription('Clear the http cache.')
          ->addArgument('slug', Argument::OPTIONAL, 'URL slug of http to delete');
    }

    protected function execute(Input $input, Output $output)
    {
        $cache = app(Cache::class);

        $slug = $input->getArgument('slug');

        if ($slug) {
            $this->forget($cache, $slug);
        } else {
            $this->clear($cache);
        }
    }

    public function forget(Cache $cache, $slug)
    {
        if ($cache->forget($slug)) {
            $this->output->writeln("Http cache cleared for \"{$slug}\"");
        } else {
            $this->output->writeln("No http cache found for \"{$slug}\"");
        }
    }

    /**
     * Clear the full page cache.
     *
     * @param  \Silber\PageCache\Cache  $cache
     * @param  string  $slug
     * @return void
     */
    public function clear(Cache $cache)
    {
        if ($cache->clear()) {
            $this->output->writeln('Page cache cleared at '.$cache->getCachePath());
        } else {
            $this->output->writeln('Page cache not cleared at '.$cache->getCachePath());
        }
    }
}
