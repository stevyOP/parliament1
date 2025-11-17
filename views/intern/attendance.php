<?php
$page_title = 'Attendance Calendar';
include 'views/layouts/header.php';

// Set default values if not provided
$month = $month ?? date('m');
$year = $year ?? date('Y');
$logs = $logs ?? [];
$profile = $profile ?? null;
?>

<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3 mb-0">
                <i class="fas fa-calendar-check me-2"></i>Attendance Calendar
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active">Attendance</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<!-- Month Navigation -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-4">
                <a href="index.php?page=intern&action=attendance&month=<?= date('m', strtotime('-1 month', mktime(0, 0, 0, $month, 1, $year))) ?>&year=<?= date('Y', strtotime('-1 month', mktime(0, 0, 0, $month, 1, $year))) ?>" 
                   class="btn btn-outline-primary">
                    <i class="fas fa-chevron-left me-2"></i>Previous Month
                </a>
            </div>
            <div class="col-md-4 text-center">
                <h4 class="mb-0"><?= date('F Y', mktime(0, 0, 0, $month, 1, $year)) ?></h4>
            </div>
            <div class="col-md-4 text-end">
                <a href="index.php?page=intern&action=attendance&month=<?= date('m', strtotime('+1 month', mktime(0, 0, 0, $month, 1, $year))) ?>&year=<?= date('Y', strtotime('+1 month', mktime(0, 0, 0, $month, 1, $year))) ?>" 
                   class="btn btn-outline-primary">
                    Next Month<i class="fas fa-chevron-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Summary Statistics -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-success"><?= count(array_filter($logs, function($log) { return $log['status'] === 'approved'; })) ?></h3>
                <p class="mb-0">Approved Logs</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-warning"><?= count(array_filter($logs, function($log) { return $log['status'] === 'pending'; })) ?></h3>
                <p class="mb-0">Pending Logs</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-danger"><?= count(array_filter($logs, function($log) { return $log['status'] === 'rejected'; })) ?></h3>
                <p class="mb-0">Rejected Logs</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-primary"><?= count($logs) ?></h3>
                <p class="mb-0">Total Logs</p>
            </div>
        </div>
    </div>
</div>

<!-- Calendar -->
<div class="card">
    <div class="card-body">
        <?php
        $first_day = mktime(0, 0, 0, $month, 1, $year);
        $days_in_month = date('t', $first_day);
        $day_of_week = date('w', $first_day);
        
        // Create logs array indexed by date
        $logs_by_date = [];
        foreach ($logs as $log) {
            $logs_by_date[$log['date']] = $log;
        }
        ?>
        
        <table class="table table-bordered">
            <thead>
                <tr class="table-light">
                    <th class="text-center">Sunday</th>
                    <th class="text-center">Monday</th>
                    <th class="text-center">Tuesday</th>
                    <th class="text-center">Wednesday</th>
                    <th class="text-center">Thursday</th>
                    <th class="text-center">Friday</th>
                    <th class="text-center">Saturday</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $day = 1;
                for ($week = 0; $week < 6 && $day <= $days_in_month; $week++) {
                    echo '<tr>';
                    for ($dow = 0; $dow < 7; $dow++) {
                        if (($week == 0 && $dow < $day_of_week) || $day > $days_in_month) {
                            echo '<td class="bg-light"></td>';
                        } else {
                            $current_date = sprintf('%04d-%02d-%02d', $year, $month, $day);
                            $is_today = ($current_date == date('Y-m-d')) ? 'border-primary border-3' : '';
                            
                            echo '<td class="align-top p-2 ' . $is_today . '" style="height: 100px; min-width: 120px;">';
                            echo '<div class="fw-bold mb-2">' . $day . '</div>';
                            
                            if (isset($logs_by_date[$current_date])) {
                                $log = $logs_by_date[$current_date];
                                $status_class = $log['status'] === 'approved' ? 'success' : ($log['status'] === 'pending' ? 'warning' : 'danger');
                                echo '<span class="badge bg-' . $status_class . ' d-block mb-1">' . ucfirst($log['status']) . '</span>';
                                echo '<small class="text-muted d-block" style="font-size: 10px;">' . htmlspecialchars(substr($log['task_description'], 0, 40)) . '...</small>';
                                echo '<a href="index.php?page=intern&action=viewLog&id=' . $log['id'] . '" class="btn btn-sm btn-outline-info mt-1" style="font-size: 10px;">View</a>';
                            } else {
                                // Check if date is within internship period
                                if ($profile && $current_date >= $profile['start_date'] && $current_date <= $profile['end_date'] && $current_date <= date('Y-m-d')) {
                                    echo '<small class="text-muted">No log</small>';
                                    if ($current_date == date('Y-m-d')) {
                                        echo '<a href="index.php?page=intern&action=addLog" class="btn btn-sm btn-primary mt-1" style="font-size: 10px;">Add Log</a>';
                                    }
                                }
                            }
                            
                            echo '</td>';
                            $day++;
                        }
                    }
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>
