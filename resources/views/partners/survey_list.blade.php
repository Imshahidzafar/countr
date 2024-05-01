@extends('layout.partners.list_master')

@section('content')
    <?php $system_currency    = DB::table('system_settings')->select('description')->where('type', 'system_currency')->get()->first(); ?>

    <style>
        input{
           border-radius: 20px;
        }
        .avatar {
          vertical-align: middle;
          width: 50px;
          height: 50px;
          border-radius: 50%;
        }
        .imageUpload
        {
            display: none;
        }

        .profileImage
        {
            /* margin-top: -40px; */
            cursor: pointer;
            width: 100%;
        }

        #profile-container {
            margin: 20px auto;
            width: 130px;
            height: 130px;
            color: white;
            justify-content: center;
            border: 1px solid #8f8989;
            overflow: hidden;
        }

        #profile-container img {
            width: 150px;
            height: 150px;
           
        }
    </style>
    <!--**********************************
        Chat box End
    ***********************************-->
    
    <div class="content-body">
        <div class="container-fluid">
            <div class="col-md-12 mb-n5">
                <div class="col-sm-12 p-md-0">
                    <div class="col-sm-12 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        @section('titleBar')
                        <span class="ml-2">Manage Survey</span>
                        @endsection
                    </div>
                </div>

                <!-- row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="basic-form" style="width: 100%;">
                                    {{-- <?php 
                                        $total_surveys_created = DB::table('survey_list')->where('users_system_id', session('id'))->count(); 
                                        $total_surveys_allowed = DB::table('users_system')->where('users_system_id', session('id'))->first()->total_surveys_allowed;
                                        if($total_surveys_created < $total_surveys_allowed){
                                    ?> --}}
                                    <legend style="float: right;"><a style="float: right;" class="btn btn-primary" href="{{url('/partners/survey_list_add')}}"> Add Survey </a></legend>
                                    {{-- <?php } ?> --}}
                                    <div class="table-responsive">
                                        <table id="example" class="table dt-responsive nowrap display min-w850">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Cover Image</th>
                                                    <th>Name</th>
                                                    <th>Partner</th>
                                                    <th>Category</th>
                                                    <th>Reward</th>
                                                    <th>Public URL</th>
                                                    <th>Total Qs.</th>
                                                    <th>Add Qs.</th>
                                                    <th>Total Responses</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($survey_list as $key => $items)
                                                <tr class="odd gradeX">
                                                    <td>{{ $key + 1 }}</td>
                                                    <td> @if($items->image)  
                                                        <img src="{{ asset($items->image)}}" width="80px" height="80px">
                                                        @else
                                                        <img src="{{asset('uploads/placeholder/default.png')}}" height="80px" width="80px">
                                                        @endif
                                                    </td>
                                                    <td>{{ $items->name }}</td>
                                                    <td><?php echo DB::table('users_partners')->where('users_partners_id', $items->users_partners_id)->first()->first_name; ?></td>
                                                    <td><?php echo DB::table('survey_categories')->where('survey_categories_id', $items->survey_categories_id)->first()->name; ?></td>
                                                    <td>
                                                        <?php echo DB::table('survey_rewards')->where('survey_rewards_id', $items->survey_rewards_id)->first()->name; ?>
                                                        (<?php echo DB::table('survey_rewards')->where('survey_rewards_id', $items->survey_rewards_id)->first()->reward; ?>)       
                                                    </td>
                                                    <td>
                                                        Survey Code : <b><i>{{$items->survey_list_id}}</i></b><br>
                                                        <a class="btn btn-warning" target="_blank" href="{{url('/users/online_survey/' . $items->survey_list_id)}}">
                                                            <i class="fa fa-link"></i> 
                                                        </a>    
                                                    </td>
                                                    <td>
                                                        @php 
                                                            $total_questions = DB::table('survey_list_qs')->where('survey_list_id', $items->survey_list_id)->get();
                                                            $count=0;
                                                            foreach ($total_questions as $key => $question) {
                                                                $answers_check = DB::table('survey_list_qs_answers')
                                                                ->where(['survey_list_qs_id'=> $question->survey_list_qs_id,'parent_qs_id'=> '0','qs_identifier'=>"Tree"])
                                                                ->first(); 
                                                                if($answers_check || $question->question_type !="Multilevel Choice"){
                                                                    $count++;
                                                                }
                                                            
                                                            }
                                                            echo $count;
                                                        @endphp
                                                        <a class="btn btn-info" href="{{url('/partners/survey_list_qs/' . $items->survey_list_id)}}">
                                                            <i class="fa fa-list"></i> 
                                                        </a>    
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-sm btn-primary"  href="{{url('/partners/survey_list_qs_add/' . $items->survey_list_id)}}">
                                                            Add <br/>Questions
                                                            {{-- <i class="fa fa-list"></i>  --}}
                                                        </a>    
                                                    </td>
                                                    <td>
                                                        <?php echo $total_reponses = DB::table('survey_list_reponses')->where('survey_list_id', $items->survey_list_id)->count(); ?>
                                                        <?php if($total_reponses > 0){ ?>
                                                        <a class="btn btn-success" target="_blank" href="{{url('/partners/survey_list_reponses/' . $items->survey_list_id)}}">
                                                            <i class="fa fa-eye"></i> 
                                                        </a>

                                                        <a class="btn btn-warning" target="_blank" href="{{url('/partners/survey_list_graphs/' . $items->survey_list_id)}}">
                                                            <i class="fa fa-bar-chart"></i> 
                                                        </a>
                                                        <?php } else { ?>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        @if ($items->status=='Active')
                                                        <span class="btn btn-success">Active</span>
                                                        @elseif ($items->status=='Deleted')
                                                        <span class="btn btn-danger">Deleted</span>
                                                        @else 
                                                        <span class="btn btn-warning">In Active</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-info" href="{{url('/partners/survey_list_edit/' . $items->survey_list_id)}}">
                                                            <i class="fa fa-pencil"></i> 
                                                        </a>

                                                        <a class="btn btn-secondary" href="{{url('/partners/survey_list_update/' . $items->survey_list_id . '/Active')}}">
                                                            <i class="fa fa-check"></i> 
                                                        </a>

                                                        <a class="btn btn-warning" href="{{url('/partners/survey_list_update/' . $items->survey_list_id . '/Inactive')}}">
                                                            <i class="fa fa-times"></i> 
                                                        </a>

                                                        <a class="btn btn-danger" href="{{url('/partners/survey_list_delete/' . $items->survey_list_id)}}">
                                                            <i class="fa fa-trash"></i> 
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection