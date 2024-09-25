<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Manage Transactions</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="../dist/img/logo1.jpg" type="image/jpeg">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
  <!-- Other CSS links... -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
<?php
include "./includes/header.php"; // This includes the PDO connection
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Manage Transactions</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active">Manage Transactions</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <!-- Display total income, expense, and savings -->
                <div>
                  <h5>Total Income: <span id="totalIncome"></span></h5>
                  <h5>Total Expenses: <span id="totalExpense"></span></h5>
                  <h5>Total Savings: <span id="totalSaving"></span></h5>
                </div>

                <canvas id="transactionsChart" style="width:100%;max-width:600px"></canvas>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <?php include "./includes/footer.php"; ?>
  
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Other JS files... -->
<script src="dist/js/adminlte.min.js"></script>

<script>
  // Fetch transactions grouped by type and category
  const categoriesData = <?php
    try {
        $stmt = $pdo->query("
          SELECT category, 
                 type, 
                 SUM(amount) AS total_amount 
          FROM transactions 
          GROUP BY category, type
        ");
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Calculate totals for income, expense, and savings
        $totalIncome = 0;
        $totalExpense = 0;
        $totalSaving = 0;

        // Format the data for Chart.js and calculate totals
        $result = [];
        foreach ($categories as $row) {
            $result[$row['category']][$row['type']] = $row['total_amount'];
            if ($row['type'] === 'income') {
                $totalIncome += $row['total_amount'];
            } elseif ($row['type'] === 'expense') {
                $totalExpense += $row['total_amount'];
            } elseif ($row['type'] === 'saving') {
                $totalSaving += $row['total_amount'];
            }
        }

        echo json_encode($result);
        echo ";\n";
        echo "totalIncome = $totalIncome;\n";
        echo "totalExpense = $totalExpense;\n";
        echo "totalSaving = $totalSaving;\n";
    } catch (PDOException $e) {
        echo json_encode([]);
    }
  ?>;

  // Prepare data for the chart
  const labels = Object.keys(categoriesData);
  const dataIncome = labels.map(category => categoriesData[category]['income'] || 0);
  const dataExpense = labels.map(category => categoriesData[category]['expense'] || 0);
  const dataSaving = labels.map(category => categoriesData[category]['saving'] || 0);

  // Display total amounts in the HTML
  document.getElementById('totalIncome').textContent = totalIncome.toFixed(2);
  document.getElementById('totalExpense').textContent = totalExpense.toFixed(2);
  document.getElementById('totalSaving').textContent = totalSaving.toFixed(2);

  // Create the chart
  const ctx = document.getElementById('transactionsChart').getContext('2d');
  const transactionsChart = new Chart(ctx, {
      type: 'bar', // Change this to 'line', 'pie', etc. for different chart types
      data: {
          labels: labels,
          datasets: [
              {
                  label: 'Income',
                  data: dataIncome,
                  backgroundColor: 'rgba(75, 192, 192, 0.5)',
                  borderColor: 'rgba(75, 192, 192, 1)',
                  borderWidth: 1
              },
              {
                  label: 'Expense',
                  data: dataExpense,
                  backgroundColor: 'rgba(255, 99, 132, 0.5)',
                  borderColor: 'rgba(255, 99, 132, 1)',
                  borderWidth: 1
              },
              {
                  label: 'Savings',
                  data: dataSaving,
                  backgroundColor: 'rgba(255, 206, 86, 0.5)',
                  borderColor: 'rgba(255, 206, 86, 1)',
                  borderWidth: 1
              }
          ]
      },
      options: {
          scales: {
              y: {
                  beginAtZero: true
              }
          }
      }
  });
</script>
</body>
</html>
