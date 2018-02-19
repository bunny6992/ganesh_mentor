@extends('layouts.app')

@section('content')
<swot inline-template>
    <div class="row">
        <div class="col-md-2" style="height:2000px;">
        <div class="sidebar-container" style="height:100%;">
            <nav class="nav-sidebar" id="sidebar" style="height:100%; background-color: #6d7fcc;margin-bottom: 0px !important;">
                <button type="button" id="sidebarCollapse" class="hidden btn btn-info navbar-btn" @click="toggleSideBar()">
                    <i class="glyphicon glyphicon-align-left"></i>
                    <span></span>
                </button>
                <div class="sidebar-header">
                    <a href="/home">
                        <h3>Business Dashboard</h3>
                        <strong>BD</strong>
                    </a>
                </div>
                <ul class="list-unstyled components">
                    <li>
                        <a href="#projectSubmenu" data-toggle="collapse" aria-expanded="false">
                            <i class="glyphicon glyphicon-folder-open"></i>
                            My Projects
                        </a>
                        <ul class="collapse list-unstyled" id="projectSubmenu">
                            <li @click="addNewProject()"><a>Add New &nbsp;<span class="glyphicon glyphicon-plus"></span></a></li>
                            <div v-for="(project, index) in projects">
                                <a :href="drowdownHREF(project.id)" data-toggle="collapse" aria-expanded="false">
                                    <i class="glyphicon glyphicon-book"></i>
                                    @{{ project.name }}
                                </a>
                                <ul class="collapse list-unstyled model-items" :id="drowdownId(project.id)">
                                    <li @click="addNewProjectModel(project.id, index)"><a>Add New &nbsp;<span class="glyphicon glyphicon-plus"></span></a></li>
                                    <li v-for="model in project.models"><a @click="getCanvas(model.id)" :class="{ 'sidebar-active': isActive('item' + model.id) }">@{{ model.name }}</a></li>
                                </ul>
                            </div>
                        </ul>
                    </li>
                    <li>
                        <a href="#toolsSubmenu" data-toggle="collapse" aria-expanded="false">
                            <i class="glyphicon glyphicon-briefcase"></i>
                            Business Analysis Tools
                        </a>
                        <ul class="collapse list-unstyled" id="toolsSubmenu">
                            <li>
                                <a href="/business-canvas">Business Canvas</a>
                            </li>
                            <li>
                                <a href="/swot" class="sidebar-active">SWOT</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">
                            <i class="glyphicon glyphicon-tag"></i>
                            About
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="glyphicon glyphicon-paperclip"></i>
                            FAQ
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="glyphicon glyphicon-send"></i>
                            Contact
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        </div>
        <div class="col-md-10" v-if="currentModel.id" style="margin-top: 50px">
            <div style="text-align: center; margin:10px">
                <h2>SWOT Analysis</h2>
            </div>
            <div class="col-container" style="min-height: 400px">
                <div class="col" style="background:#ECECEA; border:2px dashed #67BCDB; padding: 0px; width: 50%;">
                    <div style="text-align: center">
                        <h4>Strengths
                        <button class="btn btn-sm" @click="addModelItem('strengths')">
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                        </h4>
                    </div>
                      <draggable id="strengths" v-model="currentModel.strengths" style="min-height: 250px;" :options="{group:'people'}" @end="updateSections($event, true)">
                        <div v-for="(element, index) in currentModel.strengths" class="panel panel-info panels">
                            <div class="panel-heading">
                                <h3 class="panel-title">@{{element.title}}</h3>
                            </div>
                            <div class="panel-body" v-html="element.body">
                                
                            </div>
                            <div class="panel-footer" style="text-align:center; height:28px">
                                <span style="position:relative; bottom: 15px">
                                    <button class="btn btn-xs" @click="deleteItem(element.id)" style="color:white; border-radius:50%; margin:7px; cursor:pointer; background-color: crimson">
                                        <span class="glyphicon glyphicon-trash"></span>
                                    </button>
                                    <button class="btn btn-xs btn-success" @click="editItem(element, index)" style="border-radius:50%; margin:7px; cursor:pointer;">
                                        <span class="glyphicon glyphicon-pencil"></span>
                                    </button>
                                </span>
                            </div>
                        </div>
                      </draggable>
                </div>
                <div class="col" style="background:#ECECEA; border:2px dashed #67BCDB; padding: 0px; width: 50%;">
                    <div style="text-align: center">
                        <h4>Weakness
                        <button class="btn btn-sm" @click="addModelItem('weakness')">
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                        </h4>
                    </div>
                    <draggable id="weakness" v-model="currentModel.weakness" style="min-height: 250px;" :options="{group:'people'}" @end="updateSections($event, true)">
                        <div v-for="element in currentModel.weakness" class="panel panel-info panels">
                            <div class="panel-heading">
                                <h3 class="panel-title">@{{element.title}}</h3>
                            </div>
                            <div class="panel-body" v-html="element.body">
                                
                            </div>
                            <div class="panel-footer" style="text-align:center; height:28px">
                                <span style="position:relative; bottom: 15px">
                                    <button class="btn btn-xs" @click="deleteItem(element.id)" style="color:white; border-radius:50%; margin:7px; cursor:pointer; background-color: crimson">
                                        <span class="glyphicon glyphicon-trash"></span>
                                    </button>
                                    <button class="btn btn-xs btn-success" @click="editItem(element, index)" style="border-radius:50%; margin:7px; cursor:pointer;">
                                        <span class="glyphicon glyphicon-pencil"></span>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </draggable>
                </div>
            </div>
            <div class="col-container" style="min-height: 400px">
                <div class="col" style="background:#ECECEA; border:2px dashed #67BCDB; padding: 0px; width: 50%;">
                    <div style="text-align: center">
                        <h4>Threats
                        <button class="btn btn-sm" @click="addModelItem('threats')">
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                        </h4>
                    </div>
                      <draggable id="threats" v-model="currentModel.threats" style="min-height: 250px;" :options="{group:'people'}" @end="updateSections($event, true)">
                        <div v-for="element in currentModel.threats" class="panel panel-info panels">
                            <div class="panel-heading">
                                <h3 class="panel-title">@{{element.title}}</h3>
                            </div>
                            <div class="panel-body" v-html="element.body">
                                
                            </div>
                            <div class="panel-footer" style="text-align:center; height:28px">
                                <span style="position:relative; bottom: 15px">
                                    <button class="btn btn-xs" @click="deleteItem(element.id)" style="color:white; border-radius:50%; margin:7px; cursor:pointer; background-color: crimson">
                                        <span class="glyphicon glyphicon-trash"></span>
                                    </button>
                                    <button class="btn btn-xs btn-success" @click="editItem(element, index)" style="border-radius:50%; margin:7px; cursor:pointer;">
                                        <span class="glyphicon glyphicon-pencil"></span>
                                    </button>
                                </span>
                            </div>
                        </div>
                      </draggable>
                </div>
                <div class="col" style="background:#ECECEA; border:2px dashed #67BCDB; padding: 0px; width: 50%;">
                    <div style="text-align: center">
                        <h4>Opportunities
                        <button class="btn btn-sm" @click="addModelItem('opportunities')">
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                        </h4>
                    </div>
                    <draggable id="opportunities" v-model="currentModel.opportunities" style="min-height: 250px;" :options="{group:'people'}" @end="updateSections($event, true)">
                        <div v-for="element in currentModel.opportunities" class="panel panel-info panels">
                            <div class="panel-heading">
                                <h3 class="panel-title">@{{element.title}}</h3>
                            </div>
                            <div class="panel-body" v-html="element.body">
                                
                            </div>
                        </div>
                    </draggable>
                </div>
            </div>
            
            <div class="col-container" style="margin: 5% 0%">
                <div class="col" style="background:#ECECEA; border:2px dashed #67BCDB; padding: 0px;">
                    <div style="text-align: center">
                        <h4>Brainstorming Space
                        <button class="btn btn-sm" @click="addModelItem('brainstorming')">
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                        </h4>
                    </div>
                    <draggable id="brainstorming" v-model="currentModel.brainstorming" style="min-height: 250px;" :options="{group:'people'}" @end="updateSections($event, true)">
                    
                    <div class="col-md-6" v-for="element in currentModel.brainstorming">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">@{{element.title}}</h3>
                            </div>
                            <div class="panel-body" v-html="element.body">
                                
                            </div>
                            <div class="panel-footer" style="text-align:center; height:28px">
                                <span style="position:relative; bottom: 15px">
                                    <button class="btn btn-xs" @click="deleteItem(element.id)" style="color:white; border-radius:50%; margin:7px; cursor:pointer; background-color: crimson">
                                        <span class="glyphicon glyphicon-trash"></span>
                                    </button>
                                    <button class="btn btn-xs btn-success" @click="editItem(element, index)" style="border-radius:50%; margin:7px; cursor:pointer;">
                                        <span class="glyphicon glyphicon-pencil"></span>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    </draggable>
                </div>
            </div>
        </div>
        <div v-else style="text-align: center; margin: 100px"> 
            <h2>SWOT Analysis</h2>
            <img src="/images/start.jpg">
            <h4>SWOT your ideas here.<br> Start by adding a new Project using the sidebar menu or navigate to a current one.</h4>
        </div>
        <modal name="new-canvas" :click-to-close="clickToClose" scrollable height="auto" width="50%">
            <div style="margin:20px">
                <h3>New @{{ newCanvas.type }}</h3>
                <input class="form-control" v-model="newCanvas.title">
                <h3>Body</h3>
                <vue-editor id="new-canvas" v-model="newCanvas.body"></vue-editor>
                <div style="margin-top:10px">
                    <button class="btn" style="background-color: crimson; color: white" @click="$modal.hide('new-canvas')">Cancel</button>
                    <button class="btn btn-primary pull-right" @click="addNewCanvas">Add</button>
                </div>
            </div>
        </modal>
        <modal name="edit-canvas" :click-to-close="clickToClose" scrollable height="auto" width="50%">
            <div style="margin:20px">
                <h3>Edit @{{ editCanvas.type }}</h3>
                <input class="form-control" v-model="editCanvas.title">
                <h3>Body</h3>
                <vue-editor id="edit-canvas" v-model="editCanvas.body"></vue-editor>
                <div style="margin-top:10px">
                    <button class="btn" style="background-color: crimson; color: white" @click="$modal.hide('edit-canvas')">Cancel</button>
                    <button class="btn btn-primary pull-right" @click="saveCanvas">Save</button>
                </div>
            </div>
        </modal>
    </div>
</swot>
@endsection