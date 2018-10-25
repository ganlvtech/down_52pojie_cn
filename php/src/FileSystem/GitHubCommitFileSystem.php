<?php

namespace Ganlv\Down52PojieCn\FileSystem;

use Ganlv\Down52PojieCn\Helpers;

class GitHubCommitFileSystem extends AbstractFileSystem
{
    protected $commitsUrl;
    protected $rawFileUrl;
    protected $cacheMaxAge;

    public function __construct(array $options = [])
    {
        parent::__construct($options);
        $this->commitsUrl = $options['COMMITS_URL'] ?? 'https://github.com/ganlvtech/down_52pojie_cn/commits/gh-pages/list.json';
        $this->rawFileUrl = $options['RAW_FILE_URL'] ?? 'https://raw.githubusercontent.com/ganlvtech/down_52pojie_cn/gh-pages/list.json';
        $this->cacheMaxAge = $options['CACHE_MAX_AGE'] ?? 7 * 24 * 60 * 60;
    }

    public function tree()
    {
        Helpers::log("Getting github commit history at: {$this->commitsUrl} .");
        $html = Helpers::curl($this->commitsUrl);

        if (1 === preg_match('/Commits on (\w+? \d+?, \d{4})/', $html, $matches)) {
            $date = $matches[1];
            $timestamp = strtotime($date);
            Helpers::log("Most recently committed on: $date ($timestamp).");

            if (time() - $timestamp < $this->cacheMaxAge) {
                Helpers::log('File not expired.');
                $downloadFileSystem = new DownloadFileSystem([
                    'FILE_URL' => $this->rawFileUrl,
                ]);
                return $downloadFileSystem->tree();
            } else {
                Helpers::log('File expired.');
            }
        } else {
            Helpers::log('No commits found.');
        }
        return null;
    }
}
