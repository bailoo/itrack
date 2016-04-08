<?php
	include_once('../src/php/util_session_variable.php');
	include ("jpgraph/src/jpgraph.php");
	include ("jpgraph/src/jpgraph_line.php");
	include ("jpgraph/src/jpgraph_date.php");
	date_default_timezone_set('Asia/Calcutta');

	$vname = $_GET['s'];
	$data = $_SESSION[$vname];
	unset ($_SESSION[$vname]);

	if(sizeof($data)>0)
	{
		/*
		foreach($data as $datetime => $value)
		{
			$data_TS[strtotime($datetime)] = $value;
		}
		plot_date($data_TS);
		*/
		plot_date($data);
	}

	function plot_date($data)
	{
		$graph = new Graph(800,400);
 
		$graph->SetMargin(40,5,30,130);
 
		$graph->SetScale('datlin');
		$graph->title->Set("Data Graph");
 
		$graph->tabtitle->Set("Graph");
		$graph->img->SetAntiAliasing("white");
		$graph->SetFrame(false);

		$graph->xaxis->SetLabelAngle(90);
		$graph->xaxis->SetFont(FF_ARIAL,FS_NORMAL,8);
		// $graph->xaxis->title->Set("Date / Time");
		$graph->yaxis->scale->SetGrace(30);
		$graph->yaxis->title->Set("Data");

		$graph->title->SetFont(FF_FONT1,FS_BOLD);
		$graph->legend->Pos(0.05,0.5,"right","center");

		$graph->footer->left->Set("(C) IESPL");
		$graph->footer->left->SetColor("blue");
		$graph->footer->center->Set("VTS Graph Report");
		$graph->footer->center->SetColor("blue");
		// $graph->footer->center->SetFont(FF_FONT2, FS_BOLD);
		$graph->footer->right->Set(date('Y-m-d H:i:s'));
		$graph->footer->right->SetColor("blue");
 
		$plot = new ScatterPlot(array_values($data),array_keys($data));

		$plot->mark->SetType(MARK_CIRCLE);
		$plot->mark->SetColor("red");
		$plot->mark->SetWidth(1);
		// $plot->SetColor("red");
		// $plot->SetCenter();

		$graph->Add($plot);
		$graph->Stroke();
	}
?>
