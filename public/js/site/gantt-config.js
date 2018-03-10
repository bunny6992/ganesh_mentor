var App = App || {};
var myLayout,   // For gantt layout config 
myToolbar;      // For gantt toolbar config

App.gantt = (function () {
    // Called on document ready
    $(function () {
        // Call init when gantt-app pages
        if ($('#gantt_here').length) {
            turnOnTheLights();
        }
    });

    function turnOnTheLights() {
        initGanttWithLayoutAndToolbar();
        initIndentOutdentFeature();
    }

    function init() {
        gantt.config.xml_date = "%Y-%m-%d %H:%i:%s";
        gantt.config.columns =  [
            {name:"text",       label:"Task name",  tree:true, width:120},
            {name:"start_date", label:"Start date", align: "center" , width:100, template: function(item){ return !item.$virtual ? item.start_date : '' }},
            {name:"end_date",   label:"End date",   align: "center" , width:100, template: function(item){ return !item.$virtual ? item.end_date : '' }},
            {name:"priority",   label:"Priority",   align: "center" , width:100, template: function(item){ return byId(gantt.serverList('priority'), item.priority) }},
            {name:"resources",   label:"Resources",   align: "center" , width:100, template: function(item){ return byId(gantt.serverList('resources'), item.resources) }},
            {name:"duration",   label:"Duration",   align: "center" , width:100, template: function(item){ return !item.$virtual ? item.duration : '' }},
            {name:"progress",   label:"Progress",   align: "center" , width:100, template: function(item){ return Math.round(item.progress*100) + '%' || '' }},

            {name:"add", width:40}
        ];

        //default lightbox definition   
        var opts = [
            { key: 'High', label: 'High' },
            { key: 'Normal', label: 'Normal' },
            { key: 'Low', label: 'Low' }
        ];
         
        gantt.locale.labels.section_progress = "Progress";
        gantt.locale.labels.section_resources = "Resource Name";
        gantt.locale.labels.section_priority = "Priority";


        defineTaskLightBox(opts);
        defineProjectLightBox(opts);
        defineMilestoneLightBox(opts);

        initTaskGrouping();
        initExcelImportFeature();
        initMileStone();
        gantt.init("gantt_here");
        gantt.load("/api/data?userId=" + window.userId + "&projectId=" + window.projectId);

        var dp = new gantt.dataProcessor("/api");
        dp.init(gantt);
        dp.setTransactionMode("REST");

        // Calling showGroups because otherwise where we use template 
        // calback in column config settings those column values are not visible enough
        // Untill we hit groupBy callback
        showGroups();
    }

    function defineTaskLightBox(opts) {
        gantt.config.lightbox.sections = [
            {name:"description", height:40, map_to:"text", type:"textarea", focus:true},
            {name: "type", height:40, type: "typeselect", map_to: "type"},
            {name:"resources",    height:30, map_to:"resources", type:"textarea"},
            {name:"priority",    height:22, width:50, map_to:"priority", type:"select", options:opts},
            {name: "progress", height: 22, map_to: "progress", type: "select", options: getProgressOptions()},
            // {name:"progress",    height:40, map_to:"progress", type:"textarea"},
            {name:"time",        height:200, map_to:"auto", type:"time"},
            {name:"user_id",    height:40, map_to:"user_id", type:"textarea", default_value:window.userId, hidden:true},
            {name:"project_id",    height:40, map_to:"project_id", type:"textarea", default_value:window.projectId, hidden:true},
        ];
    }

    function defineProjectLightBox(opts) {
        gantt.config.lightbox.project_sections = [
            {name:"description", height:40, map_to:"text", type:"textarea", focus:true},
            {name: "type", height:40, type: "typeselect", map_to: "type"},
            {name:"resources",    height:30, map_to:"resources", type:"textarea"},
            {name:"priority",    height:22, width:50, map_to:"priority", type:"select", options:opts},
            {name: "progress", height: 22, map_to: "progress", type: "select", options: getProgressOptions()},
            // {name:"progress",    height:40, map_to:"progress", type:"textarea"},
            {name:"time",        height:200, map_to:"auto", type:"time"},
            {name:"user_id",    height:40, map_to:"user_id", type:"textarea", default_value:window.userId, hidden:true},
            {name:"project_id",    height:40, map_to:"project_id", type:"textarea", default_value:window.projectId, hidden:true},
        ];
    }

    function defineMilestoneLightBox(opts) {
        gantt.config.lightbox.milestone_sections = [
            {name:"description", height:40, map_to:"text", type:"textarea", focus:true},
            {name: "type", height:40, type: "typeselect", map_to: "type"},
            {name:"resources",    height:30, map_to:"resources", type:"textarea"},
            {name:"priority",    height:22, width:50, map_to:"priority", type:"select", options:opts},
            {name: "progress", height: 22, map_to: "progress", type: "select", options: getProgressOptions()},
            // {name:"progress",    height:40, map_to:"progress", type:"textarea"},
            {name:"time",        height:200, map_to:"auto", type:"time"},
            {name:"user_id",    height:40, map_to:"user_id", type:"textarea", default_value:window.userId, hidden:true},
            {name:"project_id",    height:40, map_to:"project_id", type:"textarea", default_value:window.projectId, hidden:true},
        ];
    }

    function initGanttWithLayoutAndToolbar() {
        myLayout = new dhtmlXLayoutObject({
            parent: "layoutObj",
            pattern: '2E'
        });
        myLayout.cells('a').fixSize(true, true);
        myLayout.cells('a').hideHeader();
        myLayout.cells('a').setHeight(32);
        myLayout.cells('b').hideHeader();

        myLayout.cells('b').attachObject('gantt_here');
        initGantt(gantt);

        myToolbar = myLayout.cells('a').attachToolbar();
        initToolbar(myToolbar);
    }

    function initGantt(gantt){
        gantt.message({
            text:"Gantt Chart For <b>"+ window.projectName +"</b>",
            expire:-1
        });
        // gantt.config.xml_date = "%d-%m-%Y";
        // gantt.config.lightbox.sections = [
        //     {name: "description", height: 70, map_to: "text", type: "textarea", focus: true},
        //     {name: "time", type: "duration", map_to: "auto"}
        // ];
        gantt.config.scale_unit = "month";
        gantt.config.date_scale = "%F, %Y";
        gantt.config.scale_height = 50;
        gantt.config.subscales = [
            {unit: "day", step: 1, date: "%j, %D"}
        ];

        gantt.templates.task_class = gantt.templates.grid_row_class = gantt.templates.task_row_class = function (start, end, task) {
            if (gantt.isSelectedTask(task.id))
                return "gantt_selected";
            if(task.$virtual)
                return "summary-row";
            if(task.type == gantt.config.types.project)
                return "hide_project_progress_drag";
        };
        init();
    }

    function initToolbar(toolbar){

        toolbar.addButton("indent", 1, "Indent");
        toolbar.setItemToolTip("indent", "Indent selected tasks");
        toolbar.addButton("outdent", 2, "Outdent");
        toolbar.setItemToolTip("outdent", "Outdent selected tasks");
        toolbar.addButton("del", 3, "Delete");
        toolbar.setItemToolTip("del", "Delete selected tasks");
        toolbar.addButton("moveForward", 4, "Move Forward");
        toolbar.setItemToolTip("moveForward", "Move selected tasks one day forward");
        toolbar.addButton("moveBackward", 5, "Move Backward");
        toolbar.setItemToolTip("moveBackward", "Move selected tasks one day backward");
        // toolbar.addSpacer("moveBackward");
        toolbar.addSeparator("sep-1", 5);
        // toolbar.addButton("groupByPriority", 6, "Group By Priority");
        // toolbar.setItemToolTip("groupByPriority", "Group by according priority");
        // toolbar.addButton("groupByResources", 7, "Group By Resources");
        // // toolbar.setItemToolTip("groupByResources", "Group by according Resources");
        // toolbar.addButton("tree", 8, "Ungrouping");
        // toolbar.setItemToolTip("tree", "Revert Grouping");
        // toolbar.addSeparator("sep-2", 9);
        toolbar.addButton("exportToExcel", 10, "Export To Excel");
        toolbar.setItemToolTip("exportToExcel", "Export To Excel");
        toolbar.attachEvent("onClick", function(id){  
            switch (id){
                case "tree":
                showGroups();   
                break;
                case "groupByPriority":
                showGroups('priority');
                break;
                case "groupByResources":
                showGroups('resources');
                break;
                case "exportToExcel":
                gantt.exportToExcel();
                break;
            }
        });


        toolbar.attachEvent("onClick", function (id) {
            gantt.performAction(id)
        });
    }

    function shiftTask(task_id, direction) {
        var task = gantt.getTask(task_id);
        task.start_date = gantt.date.add(task.start_date, direction, "day");
        task.end_date = gantt.calculateEndDate(task.start_date, task.duration);
        gantt.updateTask(task.id);
    }

    function initIndentOutdentFeature() {
        var actions = {
            "indent": function indent(task_id){
                var prev_id = gantt.getPrevSibling(task_id);
                while(gantt.isSelectedTask(prev_id)){
                    var prev = gantt.getPrevSibling(prev_id);
                    if(!prev) break;
                    prev_id = prev;
                }
                if (prev_id) {
                    var new_parent = gantt.getTask(prev_id);
                    gantt.moveTask(task_id, gantt.getChildren(new_parent.id).length, new_parent.id);
                    new_parent.type = gantt.config.types.project;
                    new_parent.$open = true;
                    gantt.updateTask(task_id);
                    gantt.updateTask(new_parent.id);
                    return task_id;
                }
                return null;
            },
            "outdent": function outdent(task_id){
                var cur_task = gantt.getTask(task_id);
                var old_parent = cur_task.parent;
                if (gantt.isTaskExists(old_parent) && old_parent != gantt.config.root_id){
                    gantt.moveTask(task_id, gantt.getTaskIndex(old_parent)+1+gantt.getTaskIndex(task_id), gantt.getParent(cur_task.parent));
                    if (!gantt.hasChild(old_parent))
                        gantt.getTask(old_parent).type = gantt.config.types.task;
                    gantt.updateTask(task_id);
                    gantt.updateTask(old_parent);
                    return task_id;
                }
                return null;
            },
            "del": function(task_id){
                gantt.deleteTask(task_id);
                return task_id;
            },
            "moveForward": function(task_id){
                shiftTask(task_id, 1);
            },
            "moveBackward": function(task_id){
                shiftTask(task_id, -1);
            },
            // "tree": function(task_id) {
            //     console.log('Tree');
            //     showGroups();
            // },
            // "groupByPriority": function(task_id) {
            //     showGroups('priority');
            // }
        };
        var cascadeAction = {
            "indent":true,
            "outdent":true,
            "del":true
        };

        gantt.performAction = function(actionName){
            var action = actions[actionName];
            if(!action)
                return;

            gantt.batchUpdate(function () {
                var updated = {};
                gantt.eachSelectedTask(function(task_id){

                    if(cascadeAction[actionName]){
                        if(!updated[gantt.getParent(task_id)]){
                            var updated_id = action(task_id);
                            updated[updated_id] = true;
                        }else{
                            updated[task_id] = true;
                        }
                    }else{
                        action(task_id);
                    }
                });
            });
        };
    }

    function initTaskGrouping() {
        // 
    }

    function byId(list, id){
        for(var i = 0; i < list.length; i++){
            // Check if id is date
            if (!isNaN(Date.parse(id))) {
                id = formatDate(id);
                // console.log(list[i].key + 'key');
            }
            if(list[i].key == id)
                return list[i].label || "";
        }
        return "";
    }

    function formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;

        return [year, month, day].join('-');
    }
    function showGroups(listname){
        gantt.serverList("priority", [
            {key:"High", label: "High"},
            {key:"Normal", label: "Normal"},
            {key:"Low", label: "Low"}
        ]);

        // gantt.templates.grid_row_class =
        //         gantt.templates.task_row_class = function(start, end, task){
        //             if(task.$virtual)
        //                 return "summary-row"
        // };
        gantt.templates.task_class=function(start, end, task){
            if(task.$virtual)
                return "summary-bar";
        };

        gantt.attachEvent("onBeforeTaskMove", function(id, parent, tindex){

        });
        if(listname){
            var relation = listname == 'userGroups' ? 'user' : listname;
            gantt.groupBy({
                groups: gantt.serverList(listname),
                relation_property: relation,
                group_id: "key",
                group_text: "label"
            });
        }else{
            gantt.groupBy(false);

        }
    }

    function initExcelImportFeature() {
        // gantt.templates.task_text = function(s,e,task){
        //     return "Export " + task.text;
        // };
        // gantt.config.columns[0].template = function(obj){
        //     return obj.text;
        // };
        // gantt.config.fit_tasks = true;
        // gantt.attachEvent("onParse", function(){
        //     gantt.eachTask(function(task){
        //         if(gantt.hasChild(task.id)){
        //             task.type = gantt.config.types.project;
        //             gantt.updateTask(task.id);
        //         }else if(task.duration === 0){
        //             task.type = gantt.config.types.milestone;
        //             gantt.updateTask(task.id);
        //         }
        //     });
        // });
    }

    function initMileStone() {
        gantt.config.types["customType"] = "type_id";
        gantt.locale.labels['type_' + "customType"] = "New Type";
    }

    function getProgressOptions() {
        return [
            {key:"0.00", label: "Not started"},
            {key:"0.1", label: "10%"},
            {key:"0.2", label: "20%"},
            {key:"0.3", label: "30%"},
            {key:"0.4", label: "40%"},
            {key:"0.5", label: "50%"},
            {key:"0.6", label: "60%"},
            {key:"0.7", label: "70%"},
            {key:"0.8", label: "80%"},
            {key:"0.9", label: "90%"},
            {key:"1", label: "Complete"}
        ];
    }

    // DYNAMIC PROGRESS BAR FUNCTIONALITY STARTS HERE
    // (function dynamicTaskType(){
    //         var delTaskParent;

    //         function checkParents(id) {
    //             setTaskType(id);
    //             var parent = gantt.getParent(id);
    //             if (parent != gantt.config.root_id) {
    //                 checkParents(parent);
    //             }
    //         }

    //         function setTaskType(id) {
    //             id = id.id ? id.id : id;
    //             var task = gantt.getTask(id);
    //             var type = gantt.hasChild(task.id) ? gantt.config.types.project : gantt.config.types.task;
    //             if (type != task.type) {
    //                 task.type = type;
    //                 gantt.updateTask(id);
    //             }
    //         }

    //         gantt.attachEvent("onParse", function () {
    //             gantt.eachTask(function(task){
    //                 setTaskType(task);
    //             });
    //         });

    //         gantt.attachEvent("onAfterTaskAdd", function onAfterTaskAdd(id) {
    //             gantt.batchUpdate(checkParents(id));
    //         });

    //         gantt.attachEvent("onBeforeTaskDelete", function onBeforeTaskDelete(id, task) {
    //             delTaskParent = gantt.getParent(id);
    //             return true;
    //         });

    //         gantt.attachEvent("onAfterTaskDelete", function onAfterTaskDelete(id, task) {
    //             if (delTaskParent != gantt.config.root_id) {
    //                 gantt.batchUpdate(checkParents(delTaskParent));
    //             }
    //         });

    //     })();

    //     // recalculate progress of summary tasks when the progress of subtasks changes
        (function dynamicProgress(){

            function calculateSummaryProgress(task){
                if (task.type != gantt.config.types.project)
                    return task.progress;
                var totalToDo = 0;
                var totalDone = 0;
                gantt.eachTask(function(child) {
                    if(child.type != gantt.config.types.project){
                        totalToDo += child.duration;
                        totalDone +=  (child.progress || 0) * child.duration;
                    }
                }, task.id);
                if(!totalToDo) return 0;
                else return totalDone / totalToDo;
            }

            function refreshSummaryProgress(id, submit) {
                if(!gantt.isTaskExists(id))
                    return;

                var task = gantt.getTask(id);
                task.progress = calculateSummaryProgress(task);

                if (!submit) {
                    gantt.refreshTask(id);
                } else {
                    gantt.updateTask(id);
                }

                if (!submit && gantt.getParent(id) !== gantt.config.root_id) {
                    refreshSummaryProgress(gantt.getParent(id), submit);
                }
            }


            gantt.attachEvent("onParse", function () {
                gantt.eachTask(function(task){
                    task.progress = calculateSummaryProgress(task);
                });
            });

            gantt.attachEvent("onAfterTaskUpdate", function(id){
                refreshSummaryProgress(gantt.getParent(id), true);
            });

            gantt.attachEvent("onTaskDrag", function(id){
                refreshSummaryProgress(gantt.getParent(id), false);
            });
            gantt.attachEvent("onAfterTaskAdd", function(id){
                refreshSummaryProgress(gantt.getParent(id), true);
            });


            (function(){
            var idParentBeforeDeleteTask = 0;
                gantt.attachEvent("onBeforeTaskDelete", function(id){
                    idParentBeforeDeleteTask = gantt.getParent(id);
                });
                gantt.attachEvent("onAfterTaskDelete", function(){
                    refreshSummaryProgress(idParentBeforeDeleteTask, true);
                });
            })();
        })();

        gantt.templates.progress_text = function(start, end, task){
            return "<span style='text-align:left;'>"+Math.round(task.progress*100)+ "% </span>";
        };

        gantt.templates.task_text=function(start,end,task){
            return "<b>Text:</b> "+task.text+",<b> Resources:</b> "+task.resources;
        };

    return {
        _showGroups : showGroups
    }
}());
