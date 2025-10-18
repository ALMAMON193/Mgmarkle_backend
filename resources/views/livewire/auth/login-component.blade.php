  <div class="min-h-screen flex items-center justify-center p-4">
      <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8">
          <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Sign In</h2>

          <form class="space-y-4" wire:submit.prevent="login">
              <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                  <input type="email" wire:model="email"
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                      placeholder="your@email.com" />
                  @error('email')
                      <span class="text-red-500 text-sm">{{ $message }}</span>
                  @enderror
              </div>

              <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                  <input type="password" wire:model="password"
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                      placeholder="••••••••" />
                  @error('password')
                      <span class="text-red-500 text-sm">{{ $message }}</span>
                  @enderror
              </div>

              <div class="flex items-center justify-between">
                  <label class="flex items-center">
                      <input type="checkbox" wire:model="remember"
                          class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                      <span class="ml-2 text-sm text-gray-600">Remember me</span>
                  </label>
                  <a href="#" class="text-sm text-indigo-600 hover:text-indigo-500">Forgot password?</a>
              </div>

              @error('login')
                  <p class="text-red-500 text-sm">{{ $message }}</p>
              @enderror

              <button type="submit"
                  class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 rounded-lg transition-colors">
                  Sign In
              </button>
          </form>

      </div>
  </div>
