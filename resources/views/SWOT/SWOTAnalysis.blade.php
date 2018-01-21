@extends('layouts.app')

@section('content')
<swot inline-template>
    <div class="container-fluid" style="margin:5%">
        <div class="row">
            <div class="col-md-3">
                <div class="panel panel-default">
                    <div class="panel-heading">SWOT Menu</div>
                    <div class="panel-body">
                       <h1>Add new <button class="btn btn-primary" @click="addNew()"><span class="glyphicon glyphicon-plus"></span></button></h1>
                       <div v-if="SWOTs.length > 0">
                        <h4 v-for="swot in SWOTs" @click="activeMe(swot)">
                            <button class="btn btn-xs btn-dark" v-html="swot.title" style="font-size:18px;max-width:95%;overflow: hidden; text-overflow: ellipsis; cursor:pointer" :id="'swot'+swot.id"></button>
                        </h4>
                       </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9" v-if="showBody">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="form-group" >
                                  <label class="">Title:</label>
                                  <input type="text" class="form-control" placeholder="what are we SWOTing for?" v-model="currentSWOT.title">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">Strength</div>
                                <div class="panel-body">
                                   <textarea name="strength" style="width:100%; min-height:100px;" v-model="currentSWOT.strength"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">Weakness</div>
                                <div class="panel-body">
                                   <textarea name="weakness" style="width:100%; min-height:100px;" v-model="currentSWOT.weakness"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">Opportunities</div>
                                <div class="panel-body">
                                   <textarea name="opportunities" style="width:100%; min-height:100px;" v-model="currentSWOT.opportunities"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">Threats</div>
                                <div class="panel-body">
                                   <textarea name="threats" style="width:100%; min-height:100px;" v-model="currentSWOT.threats"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div><button class="btn btn-primary btn-block" @click="save()"><h4>Save</h4></button></div>
                </div>
            </div>
        </div>
    </div>
</swot>
@endsection