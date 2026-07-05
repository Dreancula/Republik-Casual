<div
  x-data="internalChatSystem()"
  @toggle-chat.window="csOpen = !csOpen"
  class="fixed bottom-6 right-6 z-50">

  {{-- PANEL --}}
  <template x-cloak x-if="csOpen">
    <div
      x-transition:enter="transition ease-out duration-300"
      x-transition:enter-start="opacity-0 translate-y-4 scale-95"
      x-transition:enter-end="opacity-100 translate-y-0 scale-100"
      x-transition:leave="transition ease-in duration-200"
      x-transition:leave-start="opacity-100 translate-y-0 scale-100"
      x-transition:leave-end="opacity-0 translate-y-4 scale-95"
      class="absolute bottom-[60px] right-0 w-[360px] sm:w-[400px] h-[520px] bg-black border-l-2 border-[#FF0055] rounded-none flex flex-col overflow-hidden origin-bottom-right shadow-2xl">

      {{-- SCAN LINE --}}
      <div class="h-[2px] bg-[#FF0055]/20 relative overflow-hidden shrink-0">
        <div class="absolute inset-0 w-1/3 bg-[#FF0055] scan-line"></div>
      </div>

      {{-- HEADER --}}
      <div class="px-5 pt-4 pb-2 flex items-start justify-between shrink-0">
        <div>
          <h2 class="text-white text-2xl font-black tracking-[0.15em] leading-none">CHAT</h2>
          <p class="text-[#666] text-[10px] tracking-[0.2em] uppercase mt-1 font-medium">live support</p>
        </div>
        <button
          @click="csOpen = false"
          class="w-7 h-7 flex items-center justify-center text-[#666] hover:text-white transition-colors text-sm">
          ✕
        </button>
      </div>

      {{-- CATEGORY PILLS --}}
      <div class="px-5 py-2 flex gap-2 shrink-0 overflow-x-auto">
        <template x-for="cat in ['Umum', 'Return', 'Ukuran']" :key="cat">
          <button
            @click="kategoriChat = cat"
            :class="kategoriChat === cat
              ? 'bg-[#FF0055] text-white border-[#FF0055]'
              : 'bg-[#141414] text-[#666] border-[#141414] hover:border-[#333]'"
            class="px-3 py-1 text-[10px] font-semibold tracking-wider uppercase border transition-colors shrink-0">
            <span x-text="cat"></span>
          </button>
        </template>
      </div>

      {{-- MESSAGES --}}
      <div x-ref="chatContainer" class="flex-1 overflow-y-auto px-5 py-3 space-y-4 chat-scroll">
        <template x-for="chat in chats" :key="chat.id_chat">
          <div :class="!chat.is_admin ? 'flex justify-end' : 'flex justify-start'">
            <div :class="!chat.is_admin
              ? 'max-w-[80%]'
              : 'max-w-[80%]'">

              {{-- BOT MESSAGE --}}
              <template x-if="chat.is_admin">
                <div class="bg-[#141414] border-l-2 border-[#FF0055] px-4 py-2.5">
                  <p class="text-white text-xs leading-relaxed break-words whitespace-pre-line" x-text="chat.teks"></p>
                  <template x-if="chat.foto_chat">
                    <img :src="chat.foto_chat" class="mt-2 max-w-[180px] object-cover" />
                  </template>
                </div>
              </template>

              {{-- USER MESSAGE --}}
              <template x-if="!chat.is_admin">
                <div class="bg-[#FF0055] px-4 py-2.5">
                  <p class="text-white text-xs leading-relaxed break-words" x-text="chat.teks"></p>
                  <template x-if="chat.foto_chat">
                    <img :src="chat.foto_chat" class="mt-2 max-w-[180px] object-cover" />
                  </template>
                </div>
              </template>

              <span class="text-[#555] text-[9px] mt-1 block" x-text="formatTime(chat.tgl_chat)"></span>
            </div>
          </div>
        </template>
      </div>

      {{-- FILE PREVIEW --}}
      <template x-if="fotoInput">
        <div class="px-5 py-2 bg-[#0a0a0a] flex items-center justify-between shrink-0 border-t border-[#1a1a1a]">
          <span class="text-[10px] text-[#666]">1 file attached</span>
          <button @click="fotoInput = null" class="text-[10px] text-[#FF0055] hover:underline">remove</button>
        </div>
      </template>

      {{-- INPUT --}}
      <div class="px-5 py-3 flex items-center gap-2 shrink-0 border-t border-[#1a1a1a]">
        <label class="w-9 h-9 bg-[#141414] flex items-center justify-center cursor-pointer hover:bg-[#1f1f1f] transition-colors shrink-0 text-sm text-[#666]">
          📎
          <input type="file" name="foto_chat_input" accept="image/*" class="hidden" @change="handleFileChange">
        </label>

        <input
          type="text"
          x-model="pesanTeks"
          @keydown.enter="kirimPesan()"
          placeholder="Type a message..."
          class="flex-1 bg-[#141414] border-none px-3 py-2 text-xs text-white outline-none placeholder-[#555]">

        <button
          @click="kirimPesan()"
          class="w-9 h-9 bg-[#FF0055] flex items-center justify-center shrink-0 text-sm font-bold text-white hover:bg-[#e0004a] transition-colors">
          ➔
        </button>
      </div>

      {{-- BOTTOM BAR --}}
      <div class="h-[3px] bg-[#FF0055] shrink-0"></div>
    </div>
  </template>

  {{-- FAB --}}
  <button
    @click="csOpen = !csOpen"
    class="w-12 h-12 bg-[#FF0055] flex items-center justify-center shadow-lg hover:scale-105 transition duration-300 relative z-20 float-right">
    <span x-show="!csOpen" class="text-white font-black text-lg">CHAT</span>
    <span x-show="csOpen" x-cloak class="text-white font-bold text-sm">✕</span>
  </button>

</div>

<style>
  .chat-scroll::-webkit-scrollbar { width: 3px; }
  .chat-scroll::-webkit-scrollbar-thumb { background: #333; }
  .chat-scroll::-webkit-scrollbar-track { background: transparent; }

  .scan-line {
    animation: scan 2.5s ease-in-out infinite;
  }

  @keyframes scan {
    0%   { left: -33%; }
    100% { left: 100%; }
  }

  @media (prefers-reduced-motion: reduce) {
    .scan-line { animation: none; display: none; }
  }
</style>
