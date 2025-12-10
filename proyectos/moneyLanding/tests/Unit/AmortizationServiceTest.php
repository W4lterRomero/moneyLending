<?php

namespace Tests\Unit;

use App\Services\AmortizationService;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class AmortizationServiceTest extends TestCase
{
    public function test_generates_schedule_and_totals(): void
    {
        $service = new AmortizationService();
        $result = $service->generateSchedule(1200, 12, 12, 'monthly', Carbon::parse('2024-01-01'));

        $this->assertSame(12, $result['schedule']->count());
        $this->assertGreaterThan(0, $result['payment']);
        $this->assertEquals(1, $result['schedule']->first()['number']);
        $this->assertEquals('2024-01-01', $result['schedule']->first()['due_date']->toDateString());
    }
}
