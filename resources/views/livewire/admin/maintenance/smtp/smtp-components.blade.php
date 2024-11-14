<section class="container-fluid p-4">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-12">
                <div class="border-bottom pb-3 mb-3">
                    <div class="mb-2 mb-lg-0">
                        <h1 class="mb-1 h2 fw-bold">
                            SMTP Server Setting
                        </h1>
                        <!-- Breadcrumb -->
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{route('a.maintenance')}}">Maintenance</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    SMTP Server Setting
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-12">
                <!-- Card -->
                <div class="card mb-4">
                    <!-- Card header -->
                    <div class="card-header">
                        <h4 class="mb-0">SMTP Server Setting</h4>
                    </div>
                    <!-- Card body -->
                    <div class="card-body">
                        <!-- Form -->
                        <form class="row" wire:submit.prevent="update">
                            @csrf
                            <x-success-message />
                            <x-error-message />

                            <div class="mb-3 col-12 col-md-12 col-lg-6">
                                <label for="senderName" class="form-label">Mailer</label>
                                <input class="form-control" wire:model="mailer" type="text" {{$readonly}}>
                                @error('mailer') <span class="text-danger">{{$message}}</span> @enderror
                            </div>
                            
                            <div class="mb-3 col-12 col-md-12 col-lg-6">
                                <label for="address" class="form-label">Host</label>
                                <input class="form-control" wire:model="host" type="text" {{$readonly}}>
                                @error('host') <span class="text-danger">{{$message}}</span> @enderror
                            </div>
                            
                            <div class="mb-3 col-12 col-md-12 col-lg-6">
                                <label for="mailDriver" class="form-label">Port</label>
                                <input class="form-control" wire:model="port" type="text" {{$readonly}} >
                                @error('port') <span class="text-danger">{{$message}}</span> @enderror
                            </div>
                            
                            <div class="mb-3 col-12 col-md-12 col-lg-6">
                                <label for="userName" class="form-label">Username</label>
                                <input class="form-control" wire:model="username" type="text" {{$readonly}}>
                                @error('username') <span class="text-danger">{{$message}}</span> @enderror
                            </div>
                            
                            <div class="mb-3 col-12 col-md-12 col-lg-6">
                                <label for="mailHost" class="form-label">Password</label>
                                <input class="form-control" wire:model="password" type="text" {{$readonly}}>
                                @error('password') <span class="text-danger">{{$message}}</span> @enderror
                            </div>
                            
                            <div class="mb-3 col-12 col-md-12 col-lg-6">
                                <label for="mailPassword" class="form-label">Encryption</label>
                                <input class="form-control" wire:model="encryption" {{$readonly}}>
                                @error('encryption') <span class="text-danger">{{$message}}</span> @enderror
                            </div>
                            
                            <div class="mb-3 col-12 col-md-12 col-lg-6">
                                <label for="mailPort" class="form-label">Mail from Address</label>
                                <input class="form-control" wire:model="mail_from_address" type="text" {{$readonly}}>
                                @error('mail_from_address') <span class="text-danger">{{$message}}</span> @enderror
                            </div>
                            
                            <div class="mb-3 col-12 col-md-12 col-lg-6">
                                <label for="mailEncryption" class="form-label">Mail from Name</label>
                                <input class="form-control" wire:model="mail_from_name" type="text" {{$readonly}}>
                                @error('mail_from_name') <span class="text-danger">{{$message}}</span> @enderror
                            </div>
                            
                            <div class="col-12 col-md-12 col-lg-6">
                                @if ($edit == 0)
                                    <button class="btn btn-success" type="button" wire:click="Edit()">
                                        Edit
                                    </button>
                                @else  
                                    <button class="btn btn-primary" type="submit">
                                        Save
                                    </button>
                                @endif
                                
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

