<?php

/**
 * Send a configuration to the sign.
 *
 * @param string $configuration The configuration to send.
 */
function writetty(string $configuration)
{
    $descriptors = [
        ['pipe', 'r'],
        ['pipe', 'w'],
        ['pipe', 'w']
    ];

    $proc = proc_open(sprintf('python3 "%s"', __DIR__.'/writetty.py'), $descriptors, $pipes);

    fwrite($pipes[0], $configuration);

    fclose($pipes[0]);
    fclose($pipes[1]);
    fclose($pipes[2]);

    if (proc_close($proc) !== 0) {
        throw new RuntimeException(sprintf('Unexpected exit code %d when calling writetty. Is Python installed?', $proc));
    }
}