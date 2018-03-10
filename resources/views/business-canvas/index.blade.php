@extends('layouts.app')

@section('content')
	
	<business-canvas inline-template>        
	<div class="row">
		<div class="col-md-2" style="height: 2000px">
			<nav id="sidebar" style="background-color: #6d7fcc;margin-bottom: 0px !important; height:100%;">
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
                            <span class="glyphicon glyphicon-plus"  style="margin-left: 7px" @click="addNewProject()"></span>
                        </a>
                        <ul class="collapse list-unstyled" id="projectSubmenu">
                        	<div v-for="(project, index) in projects">
	                        	<a :href="drowdownHREF(project.id)" data-toggle="collapse" aria-expanded="false">
		                            <i class="glyphicon glyphicon-book"></i>
		                            @{{ project.name }}
		                            <span class="glyphicon glyphicon-share" style="margin-left: 7px" @click="shareProject(project.id)"></span>
		                        </a>
		                        <ul class="collapse list-unstyled model-items" :id="drowdownId(project.id)">
		                        	<a href="#swotsSubmenu" data-toggle="collapse" aria-expanded="false">
			                            <i class="glyphicon glyphicon-book"></i>
			                            SWOT
			                            <span @click="addNewProjectModel(project.id, index, 'swot')" class="glyphicon glyphicon-plus"  style="margin-left: 7px"></span>
			                        </a>
			                        <ul class="collapse list-unstyled model-items" id="swotsSubmenu">
			                        	<li v-for="model in project.models">
			                        		<a @click="getCanvas(model.id, 'swot')" :class="{ 'sidebar-active': isActive('item' + model.id) }" v-if="model.type == 'swot'">@{{ model.name }}</a>
			                        	</li>
			                        </ul>
			                        <a href="#canvasSubmenu" data-toggle="collapse" aria-expanded="false">
			                            <i class="glyphicon glyphicon-book"></i>
			                            Business Canvas
			                            <span @click="addNewProjectModel(project.id, index, 'canvas')" class="glyphicon glyphicon-plus"  style="margin-left: 7px"></span>
			                        </a>
			                        <ul class="collapse list-unstyled model-items" id="canvasSubmenu">
			                        	<li v-for="model in project.models">
			                        		<a @click="getCanvas(model.id, 'canvas')" :class="{ 'sidebar-active': isActive('item' + model.id) }" v-if="model.type == 'canvas'">@{{ model.name }}</a>
			                        	</li>
			                        </ul>
                                    <a :href="'/gantt-app?projectName=' + project.name + '&projectId=' + project.id" target="_blank">
                                        <i class="glyphicon glyphicon-equalizer"></i>
                                        Gantt Chart
                                    </a>

                                    <a :href="'/my-project-resources?projectName=' + project.name + '&projectId=' + project.id" target="_blank">
                                        <i class="glyphicon glyphicon-user"></i>
                                        My Resources
                                    </a>
		                        </ul>
	                        </div>
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
        <div class="col-md-10" v-if="currentModel.type == 'canvas'" style="margin-top: 50px;">
        	<div style="text-align: center">
        		<h2>Business Canvas Model</h2>
        	</div>
            <div class="col-container" style="min-height: 400px">
				<div class="col" style="background:#ECECEA; border:2px dashed #67BCDB; padding: 0px">
					<div style="text-align: center">
                        <h4>Key Partners
                        <button class="btn btn-sm" @click="addModelItem('partners')">
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                        </h4>
                    </div>
				      <draggable id="partners" v-model="currentModel.partners" style="min-height: 250px;" :options="{group:'people'}" @end="updateSections($event, true)">
						<div v-for="element in currentModel.partners" class="panel panel-info panels">
							<div class="panel-heading">
                                <h3 class="panel-title" v-html="element.title"></h3>
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
				<div class="col" style="background:#ECECEA; border:2px dashed #67BCDB; padding: 0px">
						<div style="min-height: 200px">
							<div style="text-align: center">
		                        <h4>Key Activities
		                        <button class="btn btn-sm" @click="addModelItem('activities')">
		                            <span class="glyphicon glyphicon-plus"></span>
		                        </button>
		                        </h4>
		                    </div>
							<draggable  id="activities" v-model="currentModel.activities" style="min-height: 250px;" :options="{group:'people'}" @end="updateSections($event, true)">
								<div v-for="element in currentModel.activities" class="panel panel-info panels">
									<div class="panel-heading">
		                                <h3 class="panel-title" v-html="element.title"></h3>
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
					  <div style="background:#ECECEA; border-top:2px dashed #67BCDB; padding: 0px">
						<div style="text-align: center">
	                        <h4>Key Resources
	                        <button class="btn btn-sm" @click="addModelItem('resources')">
	                            <span class="glyphicon glyphicon-plus"></span>
	                        </button>
	                        </h4>
	                    </div>
						<draggable id="resources" v-model="currentModel.resources" style="min-height: 250px;" :options="{group:'people'}" @end="updateSections($event, true)">
							<div v-for="element in currentModel.resources" class="panel panel-info panels">
								<div class="panel-heading">
	                                <h3 class="panel-title" v-html="element.title"></h3>
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
				<div class="col" style="background:#ECECEA; border:2px dashed #67BCDB; padding: 0px">
					<div style="text-align: center">
                        <h4>Value Propositions
                        <button class="btn btn-sm" @click="addModelItem('propositions')">
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                        </h4>
                    </div>
					<draggable id="propositions" v-model="currentModel.propositions" style="min-height: 250px;" :options="{group:'people'}" @end="updateSections($event, true)">
						<div v-for="element in currentModel.propositions" class="panel panel-info panels">
							<div class="panel-heading">
                                <h3 class="panel-title" v-html="element.title"></h3>
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
				<div class="col" style="background:#ECECEA; border:2px dashed #67BCDB; padding: 0px">
						<div style="min-height: 200px">
						  	<div style="text-align: center">
		                        <h4>Customer Relationships
		                        <button class="btn btn-sm" @click="addModelItem('relationships')">
		                            <span class="glyphicon glyphicon-plus"></span>
		                        </button>
		                        </h4>
		                    </div>
							<draggable id="relationships" v-model="currentModel.relationships" style="min-height: 250px;" :options="{group:'people'}" @end="updateSections($event, true)">
								<div v-for="element in currentModel.relationships" class="panel panel-info panels">
									<div class="panel-heading">
		                                <h3 class="panel-title" v-html="element.title"></h3>
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
					<div style="background:#ECECEA; border-top:2px dashed #67BCDB; padding: 0px">
						<div style="text-align: center">
	                        <h4>Channels
	                        <button class="btn btn-sm" @click="addModelItem('channels')">
	                            <span class="glyphicon glyphicon-plus"></span>
	                        </button>
	                        </h4>
	                    </div>
						<draggable id="channels" v-model="currentModel.channels" style="min-height: 250px;" :options="{group:'people'}" @end="updateSections($event, true)">
							<div v-for="element in currentModel.channels" class="panel panel-info panels">
								<div class="panel-heading">
	                                <h3 class="panel-title" v-html="element.title"></h3>
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
				<div class="col" style="background:#ECECEA; border:2px dashed #67BCDB; padding: 0px">
					<div style="text-align: center">
                        <h4>Customer Segments
                        <button class="btn btn-sm" @click="addModelItem('segments')">
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                        </h4>
                    </div>
					<draggable id="segments" v-model="currentModel.segments" style="min-height: 250px;" :options="{group:'people'}" @end="updateSections($event, true)">
						<div v-for="element in currentModel.segments" class="panel panel-info panels">
							<div class="panel-heading">
                                <h3 class="panel-title" v-html="element.title"></h3>
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
			<div class="col-container" style="min-height: 300px">
				<div class="col" style="background:#ECECEA; border:2px dashed #67BCDB; padding: 0px">
					<div style="text-align: center">
                        <h4>Cost Structure
                        <button class="btn btn-sm" @click="addModelItem('cost')">
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                        </h4>
                    </div>
					<draggable id="cost" v-model="currentModel.cost" style="min-height: 250px;" :options="{group:'people'}" @end="updateSections($event, true)">
						<div v-for="element in currentModel.cost" class="panel panel-info panels">
							<div class="panel-heading">
                                <h3 class="panel-title" v-html="element.title"></h3>
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
				<div class="col" style="background:#ECECEA; border:2px dashed #67BCDB; padding: 0px">
					<div style="text-align: center">
                        <h4>Revenue Streams
                        <button class="btn btn-sm" @click="addModelItem('revenue')">
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                        </h4>
                    </div>
					<draggable id="revenue" v-model="currentModel.revenue" style="min-height: 250px;" :options="{group:'people'}" @end="updateSections($event, true)">
						<div v-for="element in currentModel.revenue" class="panel panel-info panels">
							<div class="panel-heading">
                                <h3 class="panel-title" v-html="element.title"></h3>
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
                                <h3 class="panel-title" v-html="element.title"></h3>
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
		<div class="col-md-10" v-else-if="currentModel.type == 'swot'" style="margin-top: 50px">
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
                                <h3 class="panel-title" v-html="element.title"></h3>
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
                                <h3 class="panel-title" v-html="element.title"></h3>
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
                                <h3 class="panel-title" v-html="element.title"></h3>
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
                                <h3 class="panel-title" v-html="element.title"></h3>
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
                                <h3 class="panel-title" v-html="element.title"></h3>
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
            <h2>Business Workout Gyming Tools</h2>
            <img src="/images/analysis.jpg">
            <h4>Add flying colors to your ideas by providing them the intense workouts they require using our gyming tools.<br> Start by adding a new Project using the sidebar menu or navigate to a current one.</h4>
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
        <modal v-if="currentShareProject" name="share-project" :click-to-close="clickToClose" scrollable height="auto" width="50%">
            <div style="margin:20px">
                <h3>Share Project</h3>
          		<div v-if="currentShareProject">
          			<div class="row">
          				<div class="col-md-1">Select</div>
          				<div class="col-md-4">Model Name</div>
          				<div class="col-md-3">Type</div>
          				<div class="col-md-4">Permission</div>
          			</div>
          			@php
          				$serial = 1;
          			@endphp
          			<div class="row" v-for="(model, index) in currentShareProject.models">
          				<div class="col-md-1">
          					<input type="checkbox" v-model="model.selected" value="1">
          				</div>
          				<div class="col-md-4" v-html="model.name"></div>
          				<div class="col-md-3" v-html="model.type"></div>
          				<div class="col-md-4">
          					<select v-model="model.permission">
          						<option value="0">Read Only</option>
          						<option value="1">Read and Write</option>
          					</select>
          				</div>
          			</div>
          			<div class="row">
          				<label class="">Receiver's Email: </label><br>
          				<div class="col-md-3" v-for="email in currentShareProject.emails">
                			<input class="from-control receiver" type="email" placeholder="Receiver's Email" v-model="email.value">
          				</div>
         				<div class="col-md-3">
         					<button @click="addReceiver()">Add Receiver</button>
         				</div>
          			</div>
          		</div>
                <div style="margin-top:10px">
                    <button class="btn" style="background-color: crimson; color: white" @click="$modal.hide('share-project')">Cancel</button>
                    <button class="btn btn-primary pull-right" @click="share()">Share</button>
                </div>
            </div>
        </modal>
	</div>
	</business-canvas>
@endsection