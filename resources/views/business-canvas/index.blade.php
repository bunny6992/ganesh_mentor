@extends('layouts.index')

@section('content')
	
	<business-canvas inline-template>        
	<div class="row">
			<nav class="navbar navbar-default navbar-static-top" style="margin:0px">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        Founder's Gym
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right gym-navbar">
                        <!-- Authentication Links -->
                        @guest
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
                                    {{ Auth::user()->name }}
                                </a>

                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="/gyming-tools">
                                            Business Workout Tools
                                        </a>
                                    </li>
                                    <li class="project-dropdown">
                                    	{{-- <div> --}}
										  <a href="#">My Projects</a>
										  <div class="dropdown-content my-dropdown">
										    <a href="#" @click="addNewProject()">Add New</a>
										    <a v-for="(project, index) in projects" @click="setCurrentProject(project)">@{{ project.name }}
										    </a>
										  </div>
										{{-- </div> --}}
                                    </li>
                                    {{-- <li class="project-dropdown">
                                    	
										  <a href="#">Shared Projects</a>
										  <div class="dropdown-content shared-dropdown">
										  	<a v-if="sharedProjects">
                                    		Nothing's here yet
                                    	</a>
										    <a v-for="(project, index) in sharedProjects" @click="setCurrentProject(project)">@{{ project[0].project_name }}</a>
										  </div>
                                    </li> --}}
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
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
		<div class="col-md-2">
			<nav id="sidebar" style="background-color: #6d7fcc;margin-bottom: 0px !important; height:2000px;">
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
        <div v-show="!currentProject.id">
        	<p style="color: white; margin: 10px; padding: 10px; border:2px dashed darkgray;">
        		Project dock is empty.<br> Navigate to a project<br> through top-right menu bar and it will show here.
        	</p>
        </div>
        <div v-show="currentProject.id">
            <ul class="list-unstyled">
            	<div style="text-align: center">
            		<button class="btn btn-default add-model-button" style="border-radius: 50%" @click="showAddNewModel">
            			<span class="glyphicon glyphicon-plus"></span>
            		</button>
            	</div>
                <li class="project-dropdown">
					  <a href="#" v-if="showModelDropdown('swot')">SWOTS</a>
					  <div class="dropdown-content swot-dropdown">
					    <a href="#" @click="addNewProjectModel('swot')">Add New</a>
					    <a v-for="(model, index) in currentProject.models" v-if="model.type=='swot'" @click="getCanvas(model.id, 'swot', '1')">@{{ model.name }}
					    </a>
					  </div>
                </li>
                <li class="project-dropdown">
					  <a href="#" v-if="showModelDropdown('canvas')">Business Canvas</a>
					  <div class="dropdown-content canvas-dropdown">
					    <a href="#" @click="addNewProjectModel('canvas')">Add New</a>
					    <a v-for="(model, index) in currentProject.models" v-if="model.type=='canvas'" @click="getCanvas(model.id, 'canvas', '1')">@{{ model.name }}
					    </a>
					  </div>
                </li>
                <li>
	                <a :href="'/gantt-app?projectName=' + currentProject.name + '&projectId=' + currentProject.id" target="_blank">
	                    <i class="glyphicon glyphicon-equalizer"></i>
	                    Gantt Chart
	                </a>
	            </li>
	            <li>
	                <a :href="'/my-project-resources?projectName=' + currentProject.name + '&projectId=' + currentProject.id" target="_blank">
	                    <i class="glyphicon glyphicon-user"></i>
	                    My Resources
	                </a>
	            </li>
            </ul>
        </div>
            </nav>
        </div>
        <div v-show="!currentModel.type" style="text-align: center; margin: 100px"> 
            <h2>Business Workout Gyming Tools</h2>
            <img src="/images/analysis.jpg">
            <h4>Add flying colors to your ideas by providing them the intense workouts they require using our gyming tools.<br> Start by adding a new Project or navigate to a current one using the menu at top right.</h4>
        </div>
        <div class="col-md-10" v-if="currentModel.type == 'canvas'" style="margin-top: 50px;">
        	<div style="text-align: center">
        		<h2>Business Canvas Model</h2>
        	</div>
            <div class="col-container" style="min-height: 400px">
				<div class="col" style="background:#ECECEA; border:2px dashed #67BCDB; padding: 0px">
					<div style="text-align: center">
                        <h4>Key Partners
                        <button v-if="canEdit" class="btn btn-sm" @click="addModelItem('partners')">
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                        </h4>
                    </div>
                    <div v-if="canEdit">
						<draggable id="partners" v-model="currentModel.partners" style="min-height: 250px;" :options="dragOptions" @end="updateSections($event, true)">
							<div v-for="element in currentModel.partners" class="panel panel-info panels">
								<div class="panel-heading">
									<span>
							            <button class="btn btn-xs" @click="deleteItem(element.id, currentModel.type)" style="color:white; border-radius:50%; margin:7px; cursor:pointer; background-color: crimson">
							                <span class="glyphicon glyphicon-trash"></span>
							            </button>
							            <button class="btn btn-xs btn-success" @click="editItem(element)" style="border-radius:50%; margin:7px; cursor:pointer;">
							                <span class="glyphicon glyphicon-pencil"></span>
							            </button>
							        </span>
							    </div>
								<div class="panel-body" v-html="element.body">
								</div>
							</div>
						</draggable>
				    </div>
					<div v-else style="min-height: 250px;">
						<div v-for="(element, index) in currentModel.partners" class="panel panel-info panels">
							<div class="panel-heading">
								<span>
							            <button class="btn btn-xs" @click="deleteItem(element.id, currentModel.type)" style="color:white; border-radius:50%; margin:7px; cursor:pointer; background-color: crimson">
							                <span class="glyphicon glyphicon-trash"></span>
							            </button>
							            <button class="btn btn-xs btn-success" @click="editItem(element)" style="border-radius:50%; margin:7px; cursor:pointer;">
							                <span class="glyphicon glyphicon-pencil"></span>
							            </button>
							        </span>
                            </div>
							<div class="panel-body" v-html="element.body">
							</div>
						</div>
					</div>
				</div>
				<div class="col" style="background:#ECECEA; border:2px dashed #67BCDB; padding: 0px">
						<div style="min-height: 200px">
							<div style="text-align: center">
		                        <h4>Key Activities
		                        <button v-if="canEdit" class="btn btn-sm" @click="addModelItem('activities')">
		                            <span class="glyphicon glyphicon-plus"></span>
		                        </button>
		                        </h4>
		                    </div>
		                    <div v-if="canEdit">
								<draggable  id="activities" v-model="currentModel.activities" style="min-height: 250px;" :options="dragOptions" @end="updateSections($event, true)">
									<div v-for="element in currentModel.activities" class="panel panel-info panels">
										<div class="panel-heading">
											<span>
							            <button class="btn btn-xs" @click="deleteItem(element.id, currentModel.type)" style="color:white; border-radius:50%; margin:7px; cursor:pointer; background-color: crimson">
							                <span class="glyphicon glyphicon-trash"></span>
							            </button>
							            <button class="btn btn-xs btn-success" @click="editItem(element)" style="border-radius:50%; margin:7px; cursor:pointer;">
							                <span class="glyphicon glyphicon-pencil"></span>
							            </button>
							        </span>
			                            </div>
										<div class="panel-body" v-html="element.body">
										</div>
									</div>
								</draggable>
							</div>
							<div v-else>
								<div v-for="element in currentModel.activities" class="panel panel-info panels">
									<div class="panel-heading">
										<span>
							            <button class="btn btn-xs" @click="deleteItem(element.id, currentModel.type)" style="color:white; border-radius:50%; margin:7px; cursor:pointer; background-color: crimson">
							                <span class="glyphicon glyphicon-trash"></span>
							            </button>
							            <button class="btn btn-xs btn-success" @click="editItem(element)" style="border-radius:50%; margin:7px; cursor:pointer;">
							                <span class="glyphicon glyphicon-pencil"></span>
							            </button>
							        </span>
		                            </div>
									<div class="panel-body" v-html="element.body">
									</div>
								</div>
							</div>
					    </div>
					  <div style="background:#ECECEA; border-top:2px dashed #67BCDB; padding: 0px">
						<div style="text-align: center">
	                        <h4>Key Resources
	                        <button v-if="canEdit" class="btn btn-sm" @click="addModelItem('resources')">
	                            <span class="glyphicon glyphicon-plus"></span>
	                        </button>
	                        </h4>
	                    </div>
	                    <div v-if="canEdit">
							<draggable id="resources" v-model="currentModel.resources" style="min-height: 250px;" :options="dragOptions" @end="updateSections($event, true)">
								<div v-for="element in currentModel.resources" class="panel panel-info panels">
									<div class="panel-heading">
										<span>
							            <button class="btn btn-xs" @click="deleteItem(element.id, currentModel.type)" style="color:white; border-radius:50%; margin:7px; cursor:pointer; background-color: crimson">
							                <span class="glyphicon glyphicon-trash"></span>
							            </button>
							            <button class="btn btn-xs btn-success" @click="editItem(element)" style="border-radius:50%; margin:7px; cursor:pointer;">
							                <span class="glyphicon glyphicon-pencil"></span>
							            </button>
							        </span>
		                            </div>
									<div class="panel-body" v-html="element.body">
									</div>
								</div>
							</draggable>
						</div>
						<div v-else>
							<div v-for="element in currentModel.resources" class="panel panel-info panels">
								<div class="panel-heading">
									<span>
							            <button class="btn btn-xs" @click="deleteItem(element.id, currentModel.type)" style="color:white; border-radius:50%; margin:7px; cursor:pointer; background-color: crimson">
							                <span class="glyphicon glyphicon-trash"></span>
							            </button>
							            <button class="btn btn-xs btn-success" @click="editItem(element)" style="border-radius:50%; margin:7px; cursor:pointer;">
							                <span class="glyphicon glyphicon-pencil"></span>
							            </button>
							        </span>
	                            </div>
								<div class="panel-body" v-html="element.body">
								</div>
							</div>
						</div>
					  </div>
				</div>
				<div class="col" style="background:#ECECEA; border:2px dashed #67BCDB; padding: 0px">
					<div style="text-align: center">
                        <h4>Value Propositions
                        <button v-if="canEdit" class="btn btn-sm" @click="addModelItem('propositions')">
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                        </h4>
                    </div>
                    <div v-if="canEdit">
						<draggable id="propositions" v-model="currentModel.propositions" style="min-height: 250px;" :options="dragOptions" @end="updateSections($event, true)">
							<div v-for="element in currentModel.propositions" class="panel panel-info panels">
								<div class="panel-heading">
									<span>
							            <button class="btn btn-xs" @click="deleteItem(element.id, currentModel.type)" style="color:white; border-radius:50%; margin:7px; cursor:pointer; background-color: crimson">
							                <span class="glyphicon glyphicon-trash"></span>
							            </button>
							            <button class="btn btn-xs btn-success" @click="editItem(element)" style="border-radius:50%; margin:7px; cursor:pointer;">
							                <span class="glyphicon glyphicon-pencil"></span>
							            </button>
							        </span>
	                            </div>
								<div class="panel-body" v-html="element.body">
								</div>
								
							</div>
					    </draggable>
					</div>
					<div v-else style="min-height: 250px;">
						<div v-for="element in currentModel.propositions" class="panel panel-info panels">
							<div class="panel-heading">
								<span>
							            <button class="btn btn-xs" @click="deleteItem(element.id, currentModel.type)" style="color:white; border-radius:50%; margin:7px; cursor:pointer; background-color: crimson">
							                <span class="glyphicon glyphicon-trash"></span>
							            </button>
							            <button class="btn btn-xs btn-success" @click="editItem(element)" style="border-radius:50%; margin:7px; cursor:pointer;">
							                <span class="glyphicon glyphicon-pencil"></span>
							            </button>
							        </span>
                            </div>
							<div class="panel-body" v-html="element.body">
							</div>
						</div>
					</div>
				</div>
				<div class="col" style="background:#ECECEA; border:2px dashed #67BCDB; padding: 0px">
						<div style="min-height: 200px">
						  	<div style="text-align: center">
		                        <h4>Customer Relationships
		                        <button v-if="canEdit" class="btn btn-sm" @click="addModelItem('relationships')">
		                            <span class="glyphicon glyphicon-plus"></span>
		                        </button>
		                        </h4>
		                    </div>
		                    <div v-if="canEdit">
								<draggable id="relationships" v-model="currentModel.relationships" style="min-height: 250px;" :options="dragOptions" @end="updateSections($event, true)">
									<div v-for="element in currentModel.relationships" class="panel panel-info panels">
										<div class="panel-heading">
											<span>
							            <button class="btn btn-xs" @click="deleteItem(element.id, currentModel.type)" style="color:white; border-radius:50%; margin:7px; cursor:pointer; background-color: crimson">
							                <span class="glyphicon glyphicon-trash"></span>
							            </button>
							            <button class="btn btn-xs btn-success" @click="editItem(element)" style="border-radius:50%; margin:7px; cursor:pointer;">
							                <span class="glyphicon glyphicon-pencil"></span>
							            </button>
							        </span>
			                            </div>
										<div class="panel-body" v-html="element.body">
											
										</div>
									</div>
								</draggable>
							</div>
							<div v-else>
								<div v-for="element in currentModel.relationships" class="panel panel-info panels">
									<div class="panel-heading">
										<span>
							            <button class="btn btn-xs" @click="deleteItem(element.id, currentModel.type)" style="color:white; border-radius:50%; margin:7px; cursor:pointer; background-color: crimson">
							                <span class="glyphicon glyphicon-trash"></span>
							            </button>
							            <button class="btn btn-xs btn-success" @click="editItem(element)" style="border-radius:50%; margin:7px; cursor:pointer;">
							                <span class="glyphicon glyphicon-pencil"></span>
							            </button>
							        </span>
		                            </div>
									<div class="panel-body" v-html="element.body">
									</div>
								</div>
							</div>
						</div>
					<div style="background:#ECECEA; border-top:2px dashed #67BCDB; padding: 0px">
						<div style="text-align: center">
	                        <h4>Channels
	                        <button v-if="canEdit" class="btn btn-sm" @click="addModelItem('channels')">
	                            <span class="glyphicon glyphicon-plus"></span>
	                        </button>
	                        </h4>
	                    </div>
	                    <div v-if="canEdit">
							<draggable id="channels" v-model="currentModel.channels" style="min-height: 250px;" :options="dragOptions" @end="updateSections($event, true)">
								<div v-for="element in currentModel.channels" class="panel panel-info panels">
									<div class="panel-heading">
										<span>
							            <button class="btn btn-xs" @click="deleteItem(element.id, currentModel.type)" style="color:white; border-radius:50%; margin:7px; cursor:pointer; background-color: crimson">
							                <span class="glyphicon glyphicon-trash"></span>
							            </button>
							            <button class="btn btn-xs btn-success" @click="editItem(element)" style="border-radius:50%; margin:7px; cursor:pointer;">
							                <span class="glyphicon glyphicon-pencil"></span>
							            </button>
							        </span>
		                            </div>
									<div class="panel-body" v-html="element.body">
									</div>
								</div>
							</draggable>
						</div>
						<div v-else>
							<div v-for="element in currentModel.channels" class="panel panel-info panels">
								<div class="panel-heading">
									<span>
							            <button class="btn btn-xs" @click="deleteItem(element.id, currentModel.type)" style="color:white; border-radius:50%; margin:7px; cursor:pointer; background-color: crimson">
							                <span class="glyphicon glyphicon-trash"></span>
							            </button>
							            <button class="btn btn-xs btn-success" @click="editItem(element)" style="border-radius:50%; margin:7px; cursor:pointer;">
							                <span class="glyphicon glyphicon-pencil"></span>
							            </button>
							        </span>
	                            </div>
								<div class="panel-body" v-html="element.body">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col" style="background:#ECECEA; border:2px dashed #67BCDB; padding: 0px">
					<div style="text-align: center">
                        <h4>Customer Segments
                        <button v-if="canEdit" class="btn btn-sm" @click="addModelItem('segments')">
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                        </h4>
                    </div>
                   	<div v-if="canEdit">
						<draggable id="segments" v-model="currentModel.segments" style="min-height: 250px;" :options="dragOptions" @end="updateSections($event, true)">
							<div v-for="element in currentModel.segments" class="panel panel-info panels">
								<div class="panel-heading">
									<span>
							            <button class="btn btn-xs" @click="deleteItem(element.id, currentModel.type)" style="color:white; border-radius:50%; margin:7px; cursor:pointer; background-color: crimson">
							                <span class="glyphicon glyphicon-trash"></span>
							            </button>
							            <button class="btn btn-xs btn-success" @click="editItem(element)" style="border-radius:50%; margin:7px; cursor:pointer;">
							                <span class="glyphicon glyphicon-pencil"></span>
							            </button>
							        </span>
	                            </div>
								<div class="panel-body" v-html="element.body">
								</div>
								
							</div>
						</draggable>
					</div>
					<div v-else style="min-height: 250px;">
						<div v-for="element in currentModel.segments" class="panel panel-info panels">
							<div class="panel-heading">
								<span>
							            <button class="btn btn-xs" @click="deleteItem(element.id, currentModel.type)" style="color:white; border-radius:50%; margin:7px; cursor:pointer; background-color: crimson">
							                <span class="glyphicon glyphicon-trash"></span>
							            </button>
							            <button class="btn btn-xs btn-success" @click="editItem(element)" style="border-radius:50%; margin:7px; cursor:pointer;">
							                <span class="glyphicon glyphicon-pencil"></span>
							            </button>
							        </span>
                            </div>
							<div class="panel-body" v-html="element.body">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-container" style="min-height: 300px">
				<div class="col" style="background:#ECECEA; border:2px dashed #67BCDB; padding: 0px">
					<div style="text-align: center">
                        <h4>Cost Structure
                        <button v-if="canEdit" class="btn btn-sm" @click="addModelItem('cost')">
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                        </h4>
                    </div>
                    <div v-if="canEdit">
						<draggable id="cost" v-model="currentModel.cost" style="min-height: 250px;" :options="dragOptions" @end="updateSections($event, true)">
							<div v-for="element in currentModel.cost" class="panel panel-info panels">
								<div class="panel-heading">
									<span>
							            <button class="btn btn-xs" @click="deleteItem(element.id, currentModel.type)" style="color:white; border-radius:50%; margin:7px; cursor:pointer; background-color: crimson">
							                <span class="glyphicon glyphicon-trash"></span>
							            </button>
							            <button class="btn btn-xs btn-success" @click="editItem(element)" style="border-radius:50%; margin:7px; cursor:pointer;">
							                <span class="glyphicon glyphicon-pencil"></span>
							            </button>
							        </span>
	                            </div>
								<div class="panel-body" v-html="element.body">
								</div>
								
							</div>
						</draggable>
					</div>
					<div v-else style="min-height: 250px;">
						<div v-for="element in currentModel.cost" class="panel panel-info panels">
							<div class="panel-heading">
								<span>
							            <button class="btn btn-xs" @click="deleteItem(element.id, currentModel.type)" style="color:white; border-radius:50%; margin:7px; cursor:pointer; background-color: crimson">
							                <span class="glyphicon glyphicon-trash"></span>
							            </button>
							            <button class="btn btn-xs btn-success" @click="editItem(element)" style="border-radius:50%; margin:7px; cursor:pointer;">
							                <span class="glyphicon glyphicon-pencil"></span>
							            </button>
							        </span>
                            </div>
							<div class="panel-body" v-html="element.body">
							</div>
						</div>
					</div>
				</div>
				<div class="col" style="background:#ECECEA; border:2px dashed #67BCDB; padding: 0px">
					<div style="text-align: center">
                        <h4>Revenue Streams
                        <button v-if="canEdit" class="btn btn-sm" @click="addModelItem('revenue')">
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                        </h4>
                    </div>
                    <div v-if="canEdit">
						<draggable id="revenue" v-model="currentModel.revenue" style="min-height: 250px;" :options="dragOptions" @end="updateSections($event, true)">
							<div v-for="element in currentModel.revenue" class="panel panel-info panels">
								<div class="panel-heading">
									<span>
							            <button class="btn btn-xs" @click="deleteItem(element.id, currentModel.type)" style="color:white; border-radius:50%; margin:7px; cursor:pointer; background-color: crimson">
							                <span class="glyphicon glyphicon-trash"></span>
							            </button>
							            <button class="btn btn-xs btn-success" @click="editItem(element)" style="border-radius:50%; margin:7px; cursor:pointer;">
							                <span class="glyphicon glyphicon-pencil"></span>
							            </button>
							        </span>
	                            </div>
								<div class="panel-body" v-html="element.body">
								</div>
								
							</div>
						</draggable>
					</div>
					<div v-else style="min-height: 250px;">
						<div v-for="element in currentModel.revenue" class="panel panel-info panels">
							<div class="panel-heading">
								<span>
							            <button class="btn btn-xs" @click="deleteItem(element.id, currentModel.type)" style="color:white; border-radius:50%; margin:7px; cursor:pointer; background-color: crimson">
							                <span class="glyphicon glyphicon-trash"></span>
							            </button>
							            <button class="btn btn-xs btn-success" @click="editItem(element)" style="border-radius:50%; margin:7px; cursor:pointer;">
							                <span class="glyphicon glyphicon-pencil"></span>
							            </button>
							        </span>
                            </div>
							<div class="panel-body" v-html="element.body">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-container" style="margin: 5% 0%">
                <div class="col" style="background:#ECECEA; border:2px dashed #67BCDB; padding: 0px;">
                    <div style="text-align: center">
                        <h4>Brainstorming Space
                        <button v-if="canEdit" class="btn btn-sm" @click="addModelItem('brainstorming')">
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                        </h4>
                    </div>
                    <div v-if="canEdit">
	                    <draggable id="brainstorming" v-model="currentModel.brainstorming" style="min-height: 250px;" :options="dragOptions" @end="updateSections($event, true)">
	                    <div class="col-md-6" v-for="element in currentModel.brainstorming">
	                        <div class="panel panel-info">
	                            <div class="panel-heading">
	                            	<span>
							            <button class="btn btn-xs" @click="deleteItem(element.id, currentModel.type)" style="color:white; border-radius:50%; margin:7px; cursor:pointer; background-color: crimson">
							                <span class="glyphicon glyphicon-trash"></span>
							            </button>
							            <button class="btn btn-xs btn-success" @click="editItem(element)" style="border-radius:50%; margin:7px; cursor:pointer;">
							                <span class="glyphicon glyphicon-pencil"></span>
							            </button>
							        </span>
	                            </div>
	                            <div class="panel-body" v-html="element.body">
	                            </div>
	                            
	                        </div>
	                    </div>
	                    </draggable>
	                </div>
					<div v-else style="min-height: 250px;">
						<div v-for="element in currentModel.brainstorming" class="panel panel-info panels">
							<div class="panel-heading">
								<span>
							            <button class="btn btn-xs" @click="deleteItem(element.id, currentModel.type)" style="color:white; border-radius:50%; margin:7px; cursor:pointer; background-color: crimson">
							                <span class="glyphicon glyphicon-trash"></span>
							            </button>
							            <button class="btn btn-xs btn-success" @click="editItem(element)" style="border-radius:50%; margin:7px; cursor:pointer;">
							                <span class="glyphicon glyphicon-pencil"></span>
							            </button>
							        </span>
                            </div>
							<div class="panel-body" v-html="element.body">
							</div>
						</div>
					</div>
                </div>
            </div>
		</div>
		<div class="col-md-10" v-else-if="currentModel.type == 'swot'" style="margin-top: 50px">
            <div style="text-align: center; margin:10px">
                <h2>@{{ currentModel.name }}</h2>
            </div>
            <div class="col-container" style="min-height: 400px">
                <div class="col" style="background:#ECECEA; border:2px dashed #67BCDB; padding: 0px; width: 50%;">
                    <div style="text-align: center">
                        <h4>Strengths
                        <button v-if="canEdit" class="btn btn-sm" @click="addModelItem('strengths')">
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                        </h4>
                    </div>
                    <div v-if="canEdit">
	                    <draggable id="strengths" v-model="currentModel.strengths" style="min-height: 250px;" :options="dragOptions" @end="updateSections($event, true)">
	                        <div v-for="(element, index) in currentModel.strengths" class="panel panel-info panels">
	                            <div class="panel-heading">
	                            	<span>
							            <button class="btn btn-xs" @click="deleteItem(element.id, currentModel.type)" style="color:white; border-radius:50%; margin:7px; cursor:pointer; background-color: crimson">
							                <span class="glyphicon glyphicon-trash"></span>
							            </button>
							            <button class="btn btn-xs btn-success" @click="editItem(element)" style="border-radius:50%; margin:7px; cursor:pointer;">
							                <span class="glyphicon glyphicon-pencil"></span>
							            </button>
							        </span>
	                            </div>
	                            <div class="panel-body" v-html="element.body">
	                            </div>
	                        </div>
	                    </draggable>
	                </div>
					<div v-else style="min-height: 250px;">
						<div v-for="element in currentModel.strengths" class="panel panel-info panels">
							<div class="panel-heading">
								<span>
							            <button class="btn btn-xs" @click="deleteItem(element.id, currentModel.type)" style="color:white; border-radius:50%; margin:7px; cursor:pointer; background-color: crimson">
							                <span class="glyphicon glyphicon-trash"></span>
							            </button>
							            <button class="btn btn-xs btn-success" @click="editItem(element)" style="border-radius:50%; margin:7px; cursor:pointer;">
							                <span class="glyphicon glyphicon-pencil"></span>
							            </button>
							        </span>
                            </div>
							<div class="panel-body" v-html="element.body">
							</div>
						</div>
					</div>
                </div>
                <div class="col" style="background:#ECECEA; border:2px dashed #67BCDB; padding: 0px; width: 50%;">
                    <div style="text-align: center">
                        <h4>Weakness
                        <button v-if="canEdit" class="btn btn-sm" @click="addModelItem('weakness')">
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                        </h4>
                    </div>
                    <div v-if="canEdit">
	                    <draggable id="weakness" v-model="currentModel.weakness" style="min-height: 250px;" :options="dragOptions" @end="updateSections($event, true)">
	                        <div v-for="element in currentModel.weakness" class="panel panel-info panels">
	                            <div class="panel-heading">
	                            	<span>
							            <button class="btn btn-xs" @click="deleteItem(element.id, currentModel.type)" style="color:white; border-radius:50%; margin:7px; cursor:pointer; background-color: crimson">
							                <span class="glyphicon glyphicon-trash"></span>
							            </button>
							            <button class="btn btn-xs btn-success" @click="editItem(element)" style="border-radius:50%; margin:7px; cursor:pointer;">
							                <span class="glyphicon glyphicon-pencil"></span>
							            </button>
							        </span>
	                            </div>
	                            <div class="panel-body" v-html="element.body">
	                            </div>
	                        </div>
	                    </draggable>
	                </div>
					<div v-else style="min-height: 250px;">
						<div v-for="element in currentModel.weakness" class="panel panel-info panels">
							<div class="panel-heading">
								<span>
							            <button class="btn btn-xs" @click="deleteItem(element.id, currentModel.type)" style="color:white; border-radius:50%; margin:7px; cursor:pointer; background-color: crimson">
							                <span class="glyphicon glyphicon-trash"></span>
							            </button>
							            <button class="btn btn-xs btn-success" @click="editItem(element)" style="border-radius:50%; margin:7px; cursor:pointer;">
							                <span class="glyphicon glyphicon-pencil"></span>
							            </button>
							        </span>
                            </div>
							<div class="panel-body" v-html="element.body">
							</div>
						</div>
					</div>
                </div>
            </div>
            <div class="col-container" style="min-height: 400px">
                <div class="col" style="background:#ECECEA; border:2px dashed #67BCDB; padding: 0px; width: 50%;">
                    <div style="text-align: center">
                        <h4>Threats
                        <button v-if="canEdit" class="btn btn-sm" @click="addModelItem('threats')">
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                        </h4>
                    </div>
                    <div v-if="canEdit">
						<draggable id="threats" v-model="currentModel.threats" style="min-height: 250px;" :options="dragOptions" @end="updateSections($event, true)">
							<div v-for="element in currentModel.threats" class="panel panel-info panels">
							    <div class="panel-heading">
							    	<span>
							            <button class="btn btn-xs" @click="deleteItem(element.id, currentModel.type)" style="color:white; border-radius:50%; margin:7px; cursor:pointer; background-color: crimson">
							                <span class="glyphicon glyphicon-trash"></span>
							            </button>
							            <button class="btn btn-xs btn-success" @click="editItem(element)" style="border-radius:50%; margin:7px; cursor:pointer;">
							                <span class="glyphicon glyphicon-pencil"></span>
							            </button>
							        </span>
							    </div>
							    <div class="panel-body" v-html="element.body">
							        
							    </div>
							    
							</div>
						</draggable>
					</div>
					<div v-else style="min-height: 250px;">
						<div v-for="element in currentModel.threats" class="panel panel-info panels">
							<div class="panel-heading">
								<span>
							            <button class="btn btn-xs" @click="deleteItem(element.id, currentModel.type)" style="color:white; border-radius:50%; margin:7px; cursor:pointer; background-color: crimson">
							                <span class="glyphicon glyphicon-trash"></span>
							            </button>
							            <button class="btn btn-xs btn-success" @click="editItem(element)" style="border-radius:50%; margin:7px; cursor:pointer;">
							                <span class="glyphicon glyphicon-pencil"></span>
							            </button>
							        </span>
                            </div>
							<div class="panel-body" v-html="element.body">
							</div>
						</div>
					</div>
                </div>
                <div class="col" style="background:#ECECEA; border:2px dashed #67BCDB; padding: 0px; width: 50%;">
                    <div style="text-align: center">
                        <h4>Opportunities
                        <button v-if="canEdit" class="btn btn-sm" @click="addModelItem('opportunities')">
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                        </h4>
                    </div>
                    <div v-if="canEdit">
	                    <draggable id="opportunities" v-model="currentModel.opportunities" style="min-height: 250px;" :options="dragOptions" @end="updateSections($event, true)">
	                        <div v-for="element in currentModel.opportunities" class="panel panel-info panels">
	                            <div class="panel-heading">
	                            	<span>
							            <button class="btn btn-xs" @click="deleteItem(element.id, currentModel.type)" style="color:white; border-radius:50%; margin:7px; cursor:pointer; background-color: crimson">
							                <span class="glyphicon glyphicon-trash"></span>
							            </button>
							            <button class="btn btn-xs btn-success" @click="editItem(element)" style="border-radius:50%; margin:7px; cursor:pointer;">
							                <span class="glyphicon glyphicon-pencil"></span>
							            </button>
							        </span>
	                            </div>
	                            <div class="panel-body" v-html="element.body">  
	                            </div>
	                        </div>
	                    </draggable>
	                </div>
					<div v-else style="min-height: 250px;">
						<div v-for="element in currentModel.opportunities" class="panel panel-info panels">
							<div class="panel-heading">
								<span>
							            <button class="btn btn-xs" @click="deleteItem(element.id, currentModel.type)" style="color:white; border-radius:50%; margin:7px; cursor:pointer; background-color: crimson">
							                <span class="glyphicon glyphicon-trash"></span>
							            </button>
							            <button class="btn btn-xs btn-success" @click="editItem(element)" style="border-radius:50%; margin:7px; cursor:pointer;">
							                <span class="glyphicon glyphicon-pencil"></span>
							            </button>
							        </span>
                            </div>
							<div class="panel-body" v-html="element.body">
							</div>
						</div>
					</div>
                </div>
            </div>
            
            <div class="col-container" style="margin: 5% 0%">
                <div class="col" style="background:#ECECEA; border:2px dashed #67BCDB; padding: 0px;">
                    <div style="text-align: center">
                        <h4>Brainstorming Space
                        <button v-if="canEdit" class="btn btn-sm" @click="addModelItem('brainstorming')">
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                        </h4>
                    </div>
                    <div v-if="canEdit">
	                    <draggable id="brainstorming" v-model="currentModel.brainstorming" style="min-height: 250px;" :options="dragOptions" @end="updateSections($event, true)">
	                    
	                    <div class="col-md-6" v-for="element in currentModel.brainstorming">
	                        <div class="panel panel-info">
	                            <div class="panel-heading">
	                            	<span>
							            <button class="btn btn-xs" @click="deleteItem(element.id, currentModel.type)" style="color:white; border-radius:50%; margin:7px; cursor:pointer; background-color: crimson">
							                <span class="glyphicon glyphicon-trash"></span>
							            </button>
							            <button class="btn btn-xs btn-success" @click="editItem(element)" style="border-radius:50%; margin:7px; cursor:pointer;">
							                <span class="glyphicon glyphicon-pencil"></span>
							            </button>
							        </span>
	                            </div>
	                            <div class="panel-body" v-html="element.body">
	                            </div>
	                        </div>
	                    </div>
	                    </draggable>
	                </div>
					<div v-else style="min-height: 250px;">
						<div v-for="element in currentModel.brainstorming" class="panel panel-info panels">
							<div class="panel-heading">
								<span>
							            <button class="btn btn-xs" @click="deleteItem(element.id, currentModel.type)" style="color:white; border-radius:50%; margin:7px; cursor:pointer; background-color: crimson">
							                <span class="glyphicon glyphicon-trash"></span>
							            </button>
							            <button class="btn btn-xs btn-success" @click="editItem(element)" style="border-radius:50%; margin:7px; cursor:pointer;">
							                <span class="glyphicon glyphicon-pencil"></span>
							            </button>
							        </span>
                            </div>
							<div class="panel-body" v-html="element.body">
							</div>
						</div>
					</div>
                </div>
            </div>
        </div>
		<modal name="new-canvas" :click-to-close="clickToClose" scrollable height="auto" width="50%">
			<div style="margin:20px">
				<h3>New @{{ newCanvas.type }}</h3>
				<vue-editor id="new-canvas" v-model="newCanvas.body"></vue-editor>
				<div style="margin-top:10px">
					<button class="btn" style="background-color: crimson; color: white" @click="$modal.hide('new-canvas')">Cancel</button>
					<button class="btn btn-primary pull-right" @click="addNewCanvas">Add</button>
				</div>
			</div>
		</modal>
		<modal name="add-model" :click-to-close="clickToClose" scrollable height="auto" width="50%">
            <div style="margin:20px">
                <h3>Select Template</h3>
                <select v-model="newModel.type" class="form-control">
                	<option value="swot">SWOT</option>
                	<option value="canvas">Business Canvas</option>
                </select>
                <div style="margin-top:10px">
                    <button class="btn" style="background-color: crimson; color: white" @click="$modal.hide('add-model')">Cancel</button>
                    <button class="btn btn-primary pull-right" @click="addNewModel">Add</button>
                </div>
            </div>
        </modal>
		<modal name="edit-canvas" :click-to-close="clickToClose" scrollable height="auto" width="50%">
            <div style="margin:20px">
                <h3>Edit @{{ editCanvas.type }}</h3>
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