<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo />
        </x-slot>

        <x-jet-validation-errors class="mb-4" />

        @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-jet-label for="email" value="{{ __('Email') }}" />
                <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <div class="mt-4">
                <x-jet-label for="password" value="{{ __('Password') }}" />
                <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-jet-checkbox id="remember_me" name="remember" />
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
                @endif

                <x-jet-button class="ml-4">
                    {{ __('Log in') }}
                </x-jet-button>
            </div>

            <!-- 👇 このブロックを追加 -->
            <hr class="my-3">
            <div class="mt-3">
                <h3>ソーシャル・ログイン</h3>
                <div class="my-2">
                    <a href="{{ route('social_login.redirect', 'line') }}">
                        <img style="height:50px" src="/images/LINE_APP.png">
                    </a>
                </div>
                <small>
                    本ウェブサービスでは、LINEによる認証ページで許可を得た場合のみメールアドレスを取得します。<br>
                    そして、取得されたメールアドレスにつきましては本サービスのログイン以外の目的には一切使用しません。
                </small>
                @if (session('messages.danger'))
                <p>
                    {{ session('messages.danger') }}
                </p>
                @endif
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout>