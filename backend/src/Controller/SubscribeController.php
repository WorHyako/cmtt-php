<?php

namespace App\Controller;

use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;

#[Route ('/subscribe')]
class SubscribeController extends AbstractController
{
    private function clamp(int $value, int $min, int $max): int
    {
        return max($min, min($max, $value));
    }

    private function diffInMonths(DateTimeImmutable $endDate, DateTimeImmutable $startDate)
    {
        $years = (int) $endDate->format('Y') - (int) $startDate->format('Y');
        $months = (int) $endDate->format('m') - (int) $startDate->format('m');

        return ($years * 12) + $months;
    }

    #[Route('/billing',
        name: 'app_subscribe',
        requirements: ['dd' => '\d+', 'mm' => '\d+', 'yyyy' => '\d+', 'mc' => '\d+'],
        methods: ['GET'])]
    public function index(Request                  $request,
                          #[MapQueryParameter] int $dd,
                          #[MapQueryParameter] int $mm,
                          #[MapQueryParameter] int $mc,
                          #[MapQueryParameter] int $sm,
                          #[MapQueryParameter] int $yyyy): JsonResponse
    {
        $before = microtime(true);


//        $d1 = new DateTimeImmutable('2024-01-31');
//
//        for ($i = 0; $i < 248; $i++) {
//            $date = $d1->modify(sprintf('+%d month', $i));
//            $diff = $this->diffInMonths($date, $d1);
//
//            if ($diff > $i) {
//                $date = $date->modify('last day of previous month');
//            }
//
////            print_r($date->format('Y-m-d') . PHP_EOL);
//        }

        $ddStart = $dd;
        $mmStart = $mm;
        $yyyyStart = $yyyy;

        for ($i = $sm; $i <= $mc; $i += $sm) {
            $mm = $mmStart + $i;
            $yyyy = $yyyyStart + ($mm % 12 ? intdiv($mm, 12) : intdiv($mm, 12) - 1);

            $mm = $mm % 12 ?: 12;
            $isFebruary = $mm == 2;
            if ($isFebruary) {
                $vesY = !($yyyy % 4);
                $ddMax = $vesY ? 29 : 28;
            } else {
                $halfYm = $mm > 7;
                $m30 = $halfYm ? $mm % 2 : !($mm % 2);
                $ddMax = $m30 ? 30 : 31;
            }
            $dd = $this->clamp($ddStart, 1, $ddMax);
//            echo json_encode(["{$i} billing date:" => "{$dd}.{$mm}.{$yyyy}"]) . "\r\n\r\n";
//            dump();
        }

        $after = microtime(true);

        $diff = ($after - $before) / 1000000000;
        dump($diff . " e-6\n");
        exit;

        return $this->json([
            'Begin date' => [
                'day' => $dd,
                'month' => $mm,
                'year' => $yyyy
            ]]);
    }
}
