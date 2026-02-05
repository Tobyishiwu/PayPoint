<x-guest-layout>
    <div class="mb-8 text-center">
        <div style="width: 52px; height: 52px; background: linear-gradient(135deg, #2B64E3 0%, #1a4ab9 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; box-shadow: 0 8px 16px rgba(43, 100, 227, 0.2);">
            <span style="color: white; font-size: 24px; font-weight: 900;">P</span>
        </div>

        <h2 style="font-size: 20px; font-weight: 800; color: #0f172a; margin-bottom: 8px;">Security Check</h2>
        <p style="font-size: 14px; color: #64748b; line-height: 1.5;">
            {{ __('This is a secure area. Please confirm your password to continue.') }}
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div>
            <label for="password" style="display: block; font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 8px;">
                {{ __('Your Password') }}
            </label>

            <input id="password"
                   type="password"
                   name="password"
                   required
                   autocomplete="current-password"
                   style="width: 100%; padding: 14px; border-radius: 14px; border: 1.5px solid #E2E8F0; outline: none; transition: 0.2s;"
                   onfocus="this.style.borderColor='#2B64E3'; this.style.backgroundColor='#F0F7FF'"
                   onblur="this.style.borderColor='#E2E8F0'; this.style.backgroundColor='transparent'"
            />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-6">
            <button type="submit" style="width: 100%; background: #2B64E3; color: white; padding: 16px; border-radius: 16px; border: none; font-weight: 800; font-size: 15px; cursor: pointer; transition: 0.2s;" onmousedown="this.style.transform='scale(0.98)'" onmouseup="this.style.transform='scale(1)'">
                {{ __('Confirm Access') }}
            </button>
        </div>
    </form>
</x-guest-layout>
