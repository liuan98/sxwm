<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

use Hyperf\Crontab\Crontab;
return [
    'enable' => false,
    // 通过配置文件定义的定时任务
    'crontab' => [
//        (new Crontab())->setName('sleepAction')
//            ->setRule('* * * * * *')
//            ->setCallback([App\Task\FooTask::class, 'sleepAction'])
//            ->setMemo('市场读出成交人'),
//        (new Crontab())->setName('BusSleepAction')
//            ->setRule('*/2 * * * * *')
//            ->setCallback([App\Task\FooTask::class, 'BusSleepAction'])
//            ->setMemo('运营读出成交人'),
    ],
];
