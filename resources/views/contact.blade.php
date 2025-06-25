@extends('layouts.app')

@section('content')
<div class="container-fluid contact-wrapper py-5" style="background: linear-gradient(135deg, #E91E63, #9C27B0);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-lg" style="border-radius: 20px; overflow: hidden;">
                    <div class="row g-0">
                        <!-- Informations de contact -->
                        <div class="col-md-5" style="background-color: #2B2051;">
                            <div class="p-5 text-white">
                                <h2 class="mb-4" style="font-family: 'Anton', sans-serif;">Contactez-nous</h2>
                                <p class="mb-4">N'hésitez pas à nous contacter pour toute question ou suggestion.</p>

                                <div class="contact-info">
                                    <div class="mb-4">
                                        <h5 class="text-pink mb-3" style="color: #DE2A80;">Adresse</h5>
                                        <p class="mb-0"><i class="fas fa-map-marker-alt me-2"></i> 123 Rue du Sport</p>
                                        <p>50000 Meknès, Maroc</p>
                                    </div>

                                    <div class="mb-4">
                                        <h5 class="text-pink mb-3" style="color: #DE2A80;">Contact</h5>
                                        <p class="mb-2"><i class="fas fa-phone me-2"></i> +212 6XX-XXXXXX</p>
                                        <p class="mb-0"><i class="fas fa-envelope me-2"></i> contact@gymly.com</p>
                                    </div>

                                    <div class="mb-4">
                                        <h5 class="text-pink mb-3" style="color: #DE2A80;">Horaires</h5>
                                        <p class="mb-2">Lun - Ven: 6h00 - 22h00</p>
                                        <p class="mb-0">Sam - Dim: 8h00 - 20h00</p>
                                    </div>

                                    <div class="social-links mt-4">
                                        <a href="#" class="text-white me-3 fs-5"><i class="fab fa-facebook"></i></a>
                                        <a href="#" class="text-white me-3 fs-5"><i class="fab fa-instagram"></i></a>
                                        <a href="#" class="text-white me-3 fs-5"><i class="fab fa-twitter"></i></a>
                                        <a href="#" class="text-white fs-5"><i class="fab fa-linkedin"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Formulaire de contact -->
                        <div class="col-md-7">
                            <div class="p-5">
                                <h3 class="mb-4" style="color: #2B2051; font-family: 'Anton', sans-serif;">Envoyez-nous un message</h3>

                                @if(session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                <form action="{{ route('contact.send') }}" method="POST" class="contact-form">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Nom complet</label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                               value="{{ old('name') }}" required style="border-radius: 10px;">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                               value="{{ old('email') }}" required style="border-radius: 10px;">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Sujet</label>
                                        <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror"
                                               value="{{ old('subject') }}" required style="border-radius: 10px;">
                                        @error('subject')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label">Message</label>
                                        <textarea name="message" rows="5" class="form-control @error('message') is-invalid @enderror"
                                                  required style="border-radius: 10px;">{{ old('message') }}</textarea>
                                        @error('message')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-primary px-4 py-2"
                                            style="background-color: #DE2A80; border: none; border-radius: 25px; font-family: 'Antic', sans-serif;">
                                        Envoyer le message
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Carte Google Maps -->
                <div class="card mt-4 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                    <div class="card-body p-0">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3311.6576023624997!2d-5.5573899!3d33.8969332!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xda044e2951b1669%3A0x77533b2e445c5c91!2sMeknes%2C%20Morocco!5e0!3m2!1sen!2sus!4v1645000000000!5m2!1sen!2sus"
                                width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control:focus {
        border-color: #DE2A80;
        box-shadow: 0 0 0 0.25rem rgba(222, 42, 128, 0.25);
    }

    .social-links a {
        transition: all 0.3s ease;
    }

    .social-links a:hover {
        color: #DE2A80 !important;
        transform: translateY(-3px);
    }

    .btn-primary:hover {
        background-color: #2B2051 !important;
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }

    .contact-info i {
        color: #DE2A80;
    }
</style>
@endsection
