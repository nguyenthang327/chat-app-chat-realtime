

{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> --}}
@extends('layouts.app')
@section('append_css')
    <link rel="stylesheet" href="{{asset('css/chat.css')}}"/>
@endsection

@section('content')
<!-- char-area -->
<section class="message-area">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="chat-area">
          <!-- chatlist -->
          <div class="chatlist">
            <div class="modal-dialog-scrollable">
              <div class="modal-content">
                <div class="chat-header">
                  <div class="msg-search">
                    <input type="text" class="form-control" id="inlineFormInputGroup" placeholder="Search" aria-label="search">
                    <a class="add" href="#"><img class="img-fluid" src="https://mehedihtml.com/chatbox/assets/img/add.svg" alt="add"></a>
                  </div>

                  <ul class="nav nav-tabs" id="myTab" role="tablist">
                    {{-- <li class="nav-item" role="presentation">
                      <button class="nav-link active" id="Open-tab" data-bs-toggle="tab" data-bs-target="#Open" type="button" role="tab" aria-controls="Open" aria-selected="true">Open</button>
                    </li>
                    <li class="nav-item" role="presentation">
                      <button class="nav-link" id="Closed-tab" data-bs-toggle="tab" data-bs-target="#Closed" type="button" role="tab" aria-controls="Closed" aria-selected="false">Closed</button>
                    </li> --}}
                  </ul>
                </div>

                <div class="modal-body">
                  <!-- chat-list -->
                  <div class="chat-lists">
                    <div class="tab-content" id="myTabContent">
                      <div class="tab-pane fade show active" id="Open" role="tabpanel" aria-labelledby="Open-tab">
                        <!-- chat-list -->
                        <div class="chat-list">
                          @foreach ($conversations as $conversation)
                            <a href="#" class="d-flex align-items-center conversation" data-conversation_id="{{$conversation->conversation_id}}">
                              <div class="flex-shrink-0">
                                <img class="img-fluid" src="https://mehedihtml.com/chatbox/assets/img/user.png" alt="user img">
                                {{-- <span class="active"></span> --}}
                              </div>
                              <div class="flex-grow-1 ms-3">
                                <h3 class="conversation-name">{{$conversation->name}}</h3>
                                {{-- <p>front end developer</p> --}}
                              </div>
                            </a>
                          @endforeach

                        </div>
                        <!-- chat-list -->
                      </div>
                    </div>

                  </div>
                  <!-- chat-list -->
                </div>
              </div>
            </div>
          </div>
          <!-- chatlist -->

          <!-- chatbox -->
          <div class="chatbox">
            <div class="modal-dialog-scrollable">
              <div class="modal-content">
                <div class="msg-head">
                  <div class="row">
                    <div class="col-8">
                      <div class="d-flex align-items-center">
                        <span class="chat-icon"><img class="img-fluid" src="https://mehedihtml.com/chatbox/assets/img/arroleftt.svg" alt="image title"></span>
                        <div class="flex-shrink-0">
                          <img class="img-fluid" src="https://mehedihtml.com/chatbox/assets/img/user.png" alt="user img">
                        </div>
                        <div class="flex-grow-1 ms-3">
                          <h3 class="msg-head-name">Mehedi Hasan</h3>
                          {{-- <p>front end developer</p> --}}
                        </div>
                      </div>
                    </div>
                    <div class="col-4">
                      <ul class="moreoption">
                        <li class="navbar nav-item dropdown">
                          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></a>
                          <ul class="dropdown-menu">
                            {{-- <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li>
                              <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li> --}}
                          </ul>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>

                <div class="modal-body">
                  <div class="msg-body">
                    <ul>
                      {{-- <li class="sender">
                        <p> Hey, Are you there? </p>
                        <span class="time">10:06 am</span>
                      </li>
                      <li class="repaly">
                        <p>yes!</p>
                        <span class="time">10:20 am</span>
                      </li> --}}
                    </ul>
                  </div>
                </div>

                <div class="send-box">
                  <form action="" id="form-send">
                    <input type="text" name="content" class="form-control" aria-label="message…" placeholder="Write message…">
                    <button type="submit"><i class="fa fa-paper-plane" aria-hidden="true"></i> Send</button>
                  </form>

                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- chatbox -->

      </div>
    </div>
  </div>
  </div>
</section>
<!-- char-area -->

@php
  $userID = auth()->user()->id;   
@endphp

@endsection

@section('append_js')
    <script type="module">
      $(function(){
        let ip_address = '127.0.0.1';
        let port = '3000';
        let socket = io(ip_address + ':' + port, { transports: ['websocket', 'polling', 'flashsocket'] });
        socket.on()
        socket.emit('conversations', {conversations: {{$conversations->pluck('conversation_id')}} })
        socket.on('message', function(data){
          let displayMessage = data.user_id == {{$userID}} ? 'repaly' : 'sender';
          let conversationId =  $('.chat-list .conversation.conversation-active').data('conversation_id');
          if(conversationId == data.conversation_id){
            $('.msg-body ul').append(`
                <li class="${displayMessage}">
                  <p>${data.content}</p>
                </li>
            `);
            scrollToBottom();
          }
        })

        let conversationId = $('.chat-list .conversation').first().data('conversation_id');
        let conversationName = $('.chat-list .conversation-name').first().text();
        $('.msg-head .msg-head-name').text(conversationName);
        $('.chat-list .conversation').first().addClass('conversation-active');
        showMessage(conversationId);
        
        $('.chat-list .conversation').on('click', function(){
          conversationId = $(this).data('conversation_id');
          $('.chat-list .conversation').removeClass('conversation-active');
          $(this).addClass('conversation-active');
          conversationName = $(this).find('.conversation-name').text();
          $('.msg-head .msg-head-name').text(conversationName);
          showMessage(conversationId);
        });

        function showMessage(conversationId){
          if(!conversationId) return;
          socket.emit('joinRoom', {'client' : {{auth()->user()->id}}, 'conversation' : conversationId});
          $.ajax({
            headers: { 'X-CSRF-Token' : $('meta[name="csrf-token"]').attr('content') },
            type: "GET",
            url: "{{route('chat.conversation.detail')}}",
            data: {
            'conversation_id': conversationId,
            },
            success: function(response) {
              let html = '';
              if(response.data.length == 0){
                html = 'Không có tin nhắn nào';
              }else{
                $.each(response.data, function(index, value){
                  html += `
                  <li class="${value.repaly == 1 ? 'repaly' : 'sender'}">
                    <p>${value.content}</p>
                  </li>`;
                })
              }
              $('.msg-body ul').html(html);
              scrollToBottom();
            },
            error: function(e) {
              console.log(e);
            // $('.msg-body ul').html('Lỗi rồi');
            }
          });
              }

        async function sendMsg(){
          await $.ajax({
            headers: { 'X-CSRF-Token' : $('meta[name="csrf-token"]').attr('content') },
            type: "POST",
            url: "{{route('chat.conversation.sendMsg')}}",
            data: {
              'content': $('#form-send input[name="content"]').val(),
              'conversation_id': conversationId,
            },
            success: function(response) {
              // $('.msg-body ul').html();
              $('#form-send input[name="content"]').val('');
              // $('.msg-body ul').append(`
              //   <li class="repaly">
              //     <p>${response.data.content}</p>
              //   </li>
              // `)
              
            },
            error: function(e) {
              console.log(e);
            }
          });
        }

        $("#form-send").on('submit', function(e){
          e.preventDefault();
          sendMsg();
        })

      function scrollToBottom(){
        var objDiv = $(".modal-dialog-scrollable .modal-body")[1];
        objDiv.scrollTop = objDiv.scrollHeight;
      }
      });
    </script>
@endsection