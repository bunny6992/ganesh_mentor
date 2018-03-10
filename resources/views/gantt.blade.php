@extends('layouts.app')

@section('content')
    <div id="layoutObj"></div>
    <input type='button' id='default' onclick="App.gantt._showGroups()" value="Tree">
    <input type='button' id='priority' onclick="App.gantt._showGroups('priority')" value="Group by priority">
    <div id="gantt_here" style='height:465px !important;'></div>
@endsection

@section('scripts')
    <script src="{{ asset('js/libs/dhtmlx.js') }}"></script>
    <script src="{{ asset('js/libs/dhtmlxgantt.js') }}" type='text/javascript' charset='utf-8'></script>
    <script src="{{ asset('js/libs/dhtmlxgantt_multiselect.js') }}" type='text/javascript' charset='utf-8'></script>
    <script src="https://export.dhtmlx.com/gantt/api.js" type="text/javascript" charset="utf-8"></script>
    <script src="{{ asset('js/site/gantt-config.js') }}"></script>
    <script src="{{ asset('js/libs/dhtmlxgantt_grouping.js') }}"></script>
    
    <script type="text/javascript">
        window.userId = '{{Auth::user()->id}}';
        window.projectId = '{{$projectId}}';
        window.projectName = '{{$projectName}}';
        
        var resourcesData = [],
        startdateData = [],
        endateData = [];

        @foreach($taskData as $value)
            resourcesData.push({key : '{{$value->resources}}', label : '{{$value->resources}}'});
            startdateData.push({key : '{{date('Y-m-d', strtotime($value->start_date))}}', label : '{{date('Y-m-d', strtotime($value->start_date))}}'});
            endateData.push({key : '{{date('Y-m-d', strtotime($value->end_date))}}', label : '{{date('Y-m-d', strtotime($value->end_date))}}'});
        @endforeach
        // console.log(startdateData);
        gantt.serverList("resources", resourcesData);
        gantt.serverList("start_date", startdateData);
        gantt.serverList("end_date", endateData);

    </script>
@endsection

@section('styles')
    <link rel='stylesheet' type='text/css' href="{{ asset('css/skins/skyblue/dhtmlx.css') }}" />

    <link rel='stylesheet' href="{{ asset('css/dhtmlxgantt.css') }}" type='text/css' media='screen' title='no title' charset='utf-8'>
    
    <style type="text/css">
        {{-- html, body{
            height:100%;
            padding:0px;
            margin:0px;
            overflow: hidden;
        } --}}
        html, body{ padding:0px; margin:0px; height:100%; overflow: hidden;}

        #gantt_here {
            height: 100%;
        }

        div#layoutObj {
            position: relative;
            margin-top: 10px;
            margin-left: 2px;
            margin-right: 2px;
            width: 100%;
            height: 600px;
        }

        .summary-row,
        .summary-row.odd{
            background-color:#EEEEEE;
            font-weight: bold;
        }
        .gantt_grid div,
        .gantt_data_area div{
            font-size:12px;
        }

        .summary-bar{
            opacity: 0.4;
        }


        {{-- When adding dynamic progress and finalizing code before implementation of predcessor and successor  --}}
        .gantt_task_line.gantt_dependent_task {
            background-color: #65c16f;
            border: 1px solid #3c9445;
        }
        .gantt_task_line.gantt_dependent_task .gantt_task_progress {
            background-color: #46ad51;
        }
        .hide_project_progress_drag .gantt_task_progress_drag{
            visibility: hidden;
        }
        .gantt_task_progress{
            text-align:left;
            padding-left:10px;
            box-sizing: border-box;
            color: white;
            font-weight: bold;
        }
    </style>
@endsection