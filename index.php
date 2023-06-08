<?php
echo "Game Launcher Web Viewer";

$DBHost = 'localhost';
$DBName = 'gl';
$DBTable = 'gl_table1';
$DBUser = 'username';
$DBPass = 'password';

$searchcmd = '';
$searchOrder = '';

if(isset($_GET['title']) && $_GET['title'] !== '')
{
    $searchcmd = "GAME_NAME LIKE '%" . htmlspecialchars_decode($_GET['title']) . "%'";
}

if(isset($_GET['status']) && $_GET['status'] !== '')
{
    $searchcmd = (mb_strlen($searchcmd) > 0 ? $searchcmd." AND " : "")."status = '".htmlspecialchars_decode($_GET['status'])."'";
}

if(isset($_GET['orderTarget']))
{
    $searchOrder = $_GET['orderTarget'] . " " . $_GET['orderFlg'];
}

$pdo = new PDO('mysql:dbname='.$DBName.';host='.$DBHost.';', $DBUser, $DBPass);
$queryStmt = 'SELECT * FROM '.$DBTable;

if(!empty($searchcmd))
{
    $queryStmt .= ' WHERE ' . $searchcmd;
}

if(!empty($searchOrder))
{
    $queryStmt .= " ORDER BY " . $searchOrder;
}

$stmt = $pdo->prepare($queryStmt);
$stmt->execute();

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<html lang='ja'>";
echo "  <head>";
echo "      <title>GLWeb</title>";
echo "      <style>";
echo "          .search-form {";
echo "              margin-bottom: 20px;";
echo "          }";
echo "          .search-input {";
echo "              padding: 5px;";
echo "              border: 1px solid #ccc;";
echo "              border-radius: 5px;";
echo "          }";
echo "          .search-button {";
echo "              padding: 5px 10px;";
echo "              background-color: #4CAF50;";
echo "              color: white;";
echo "              border: none;";
echo "              border-radius: 5px;";
echo "          }";
echo "          .order-select {";
echo "              padding: 5px;";
echo "              border: 1px solid #ccc;";
echo "              border-radius: 5px;";
echo "          }";
echo "          .custom-table {";
echo "              width: 100%;";
echo "              border-collapse: collapse;";
echo "          }";
echo "          .custom-table th, .custom-table td {";
echo "              padding: 8px;";
echo "              border: 1px solid #ccc;";
echo "          }";
echo "          .custom-table th {";
echo "              background-color: #f2f2f2;";
echo "              text-align: left;";
echo "              cursor: pointer;";
echo "          }";
echo "      </style>";
echo "      <script>";
echo "          function sortTable(n) {";
echo "              var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;";
echo "              table = document.getElementById('customTable');";
echo "              switching = true;";
echo "              dir = 'asc';";
echo "              while (switching) {";
echo "                  switching = false;";
echo "                  rows = table.getElementsByTagName('tr');";
echo "                  for (i = 1; i < (rows.length - 1); i++) {";
echo "                      shouldSwitch = false;";
echo "                      x = rows[i].getElementsByTagName('td')[n];";
echo "                      y = rows[i + 1].getElementsByTagName('td')[n];";
echo "                      if (dir === 'asc') {";
echo "                          if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {";
echo "                              shouldSwitch = true;";
echo "                              break;";
echo "                          }";
echo "                      } else if (dir === 'desc') {";
echo "                          if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {";
echo "                              shouldSwitch = true;";
echo "                              break;";
echo "                          }";
echo "                      }";
echo "                  }";
echo "                  if (shouldSwitch) {";
echo "                      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);";
echo "                      switching = true;";
echo "                      switchcount++;";
echo "                  } else {";
echo "                      if (switchcount === 0 && dir === 'asc') {";
echo "                          dir = 'desc';";
echo "                          switching = true;";
echo "                      }";
echo "                  }";
echo "              }";
echo "          }";
echo "      </script>";
echo "  </head>";
echo "  <body>";
echo "      <div class='search-form'>";
echo "          <form method='get' action='index.php'>";
echo "              <input type='text' name='title' placeholder='ゲームタイトルを入力' value='".htmlspecialchars_decode($_GET['title'])."' class='search-input'>";
echo "              <select name='status' value='".htmlspecialchars_decode($_GET['status'])."' class='order-select'>";
echo "                  <option value=''>全て</option>";
echo "                  <option value='未プレイ'>未プレイ</option>";
echo "                  <option value='プレイ中'>プレイ中</option>";
echo "                  <option value='プレイ済'>プレイ済</option>";
echo "                  <option value='攻略済'>攻略済</option>";
echo "              </select>";
echo "              <select name='orderTarget' class='order-select' value='".htmlspecialchars_decode($_GET['orderTarget'])."'>";
echo "                  <option value='GAME_NAME'>タイトル</option>";
echo "                  <option value='STATUS'>ステータス</option>";
echo "                  <option value='RUN_COUNT'>起動回数</option>";
echo "                  <option value='UPTIME'>起動時間</option>";
echo "                  <option value='LAST_RUN'>最終起動</option>";
echo "              </select>";
echo "              <select name='orderFlg' class='order-select' value='".$_GET['orderFlg']."'>";
echo "                  <option value='ASC'>昇順</option>";
echo "                  <option value='DESC'>降順</option>";
echo "              </select>";
echo "              <input type='submit' value='検索' class='search-button'>";
echo "          </form>";
echo "      </div>";
echo "      <table id='customTable' class='custom-table'>";
echo "          <tr>";
echo "              <th onclick='sortTable(0)'>タイトル</th>";
echo "              <th onclick='sortTable(1)'>ステータス</th>";
echo "              <th onclick='sortTable(2)'>起動回数</th>";
echo "              <th onclick='sortTable(3)'>起動時間</th>";
echo "              <th onclick='sortTable(4)'>最終起動</th>";
echo "          </tr>";

foreach($results as $row) {
    $total = (int) $row['UPTIME'];
    $runCount = (int) $row['RUN_COUNT'];
    $hour = floor($total / 60 / 60);
    $min = floor(($total / 60) % 60);
    echo "<tr>";
    echo "<td>" . $row['GAME_NAME'] . "</td>";
    echo "<td>" . $row['STATUS'] . "</td>";
    echo "<td>" . $runCount . "</td>";
    echo "<td>" . $hour . "時間" . $min . "分</td>";
    echo "<td>" . $row['LAST_RUN'] . "</td>";
    echo "</tr>";
}

echo "      </table>";
echo "<small><a href='https://github.com/dekotan24/GLWeb' target='_blank'>GitHub</a> | Executed Query: ".$queryStmt."</small>";
echo "  </body>";
echo "</html>";

unset($pdo);
?>
