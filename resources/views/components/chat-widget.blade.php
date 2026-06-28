{{-- FLOATING INTERNAL CUSTOM SYSTEM CHAT --}}
<div class="fixed bottom-6 right-6 z-50 flex items-end justify-end" x-data="internalChatSystem()" @toggle-chat.window="csOpen = !csOpen">
    <div class="relative">
        
        <div x-cloak 
             x-show="csOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 scale-95"
             class="absolute bottom-[60px] right-0 w-[360px] sm:w-[400px] h-[520px] glass rounded-[28px] shadow-2xl border border-neutral-200/60 flex flex-col overflow-hidden origin-bottom-right z-10">
            
            <div class="p-4 border-b border-neutral-200/60 flex items-center justify-between bg-black/[0.02]">
                <div class="flex items-center gap-3">
                    <div class="relative w-10 h-10">
                        <img src="{{ asset('image/icon_rc.png') }}"
                             alt="RC"
                             class="w-10 h-10 rounded-full object-cover">
                        <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 rounded-full border-2 border-white animate-pulse"></span>
                    </div>
                    <div>
                        <h4 class="font-bold text-xs tracking-wide uppercase">Live Support</h4>
                        <p class="text-[10px] text-neutral-600">Internal Customer Chat</p>
                    </div>
                </div>
                <button @click="csOpen = false" class="w-8 h-8 rounded-xl glass flex items-center justify-center text-xs">✕</button>
            </div>

            <div class="px-4 py-2 border-b border-neutral-200/40 flex items-center gap-2 bg-neutral-100/50">
                <label class="text-[10px] uppercase font-bold text-neutral-500 shrink-0">Kategori:</label>
                <select x-model="kategoriChat" class="text-xs bg-transparent border-none outline-none font-medium text-neutral-700 cursor-pointer p-0">
                    <option value="Umum" class="dark:bg-zinc-900">Pertanyaan Umum</option>
                    <option value="Return" class="dark:bg-zinc-900">Pengajuan Return (Komplain)</option>
                    <option value="Ukuran" class="dark:bg-zinc-900">Konsultasi Ukuran</option>
                </select>
            </div>

            <div x-ref="chatContainer" class="flex-1 overflow-y-auto p-4 space-y-4 chat-scroll">
                <template x-for="chat in chats" :key="chat.id_chat">
                    <div :class="!chat.is_admin ? 'flex justify-end' : 'flex justify-start'">
                        <div :class="!chat.is_admin ? 'max-w-[80%] items-end flex flex-col' : 'max-w-[80%] items-start flex flex-col'">
                            
                            <span class="text-[9px] text-neutral-500 mb-1 px-1" x-text="'[' + chat.kategori_chat + ']'"></span>
                            
                            <div :class="!chat.is_admin 
                                ? 'bg-black text-white rounded-[20px] rounded-tr-sm px-4 py-2.5 text-xs shadow-sm' 
                                : 'glass rounded-[20px] rounded-tl-sm px-4 py-2.5 text-xs border border-neutral-200'">
                                
                                <p class="leading-relaxed break-words" x-text="chat.teks"></p>
                                
                                <template x-if="chat.foto_chat">
                                    <div class="mt-2 rounded-lg overflow-hidden border border-neutral-200/20 max-w-[180px]">
                                        <img :src="chat.foto_chat" alt="Attachment" class="w-full object-cover">
                                    </div>
                                </template>
                            </div>
                            
                            <span class="text-[9px] text-neutral-500 mt-1 px-1" x-text="formatTime(chat.tgl_chat)"></span>
                        </div>
                    </div>
                </template>
            </div>

            <template x-if="fotoInput">
                <div class="px-4 py-2 bg-neutral-100/40 border-t border-neutral-200/40 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <img :src="fotoInput" class="w-10 h-10 object-cover rounded-lg border">
                        <span class="text-[10px] text-neutral-500">Gambar siap dikirim...</span>
                    </div>
                    <button @click="fotoInput = null" class="text-xs text-red-500 hover:underline">Batal</button>
                </div>
            </template>

            <div class="p-3 border-t border-neutral-200/60 flex items-center gap-2 bg-black/[0.01]">
                <label class="w-9 h-9 rounded-xl glass flex items-center justify-center cursor-pointer hover:bg-neutral-100 shrink-0 transition text-sm">
                    📷
                    <input type="file" name="foto_chat_input" accept="image/*" class="hidden" @change="handleFileChange">
                </label>

                <input type="text" 
                       x-model="pesanTeks"
                       @keydown.enter="kirimPesan()"
                       placeholder="Ketik pesan di sini..." 
                       class="flex-1 bg-white/50 border border-neutral-200 rounded-xl px-3 py-2 text-xs outline-none focus:border-black transition">
                
                <button @click="kirimPesan()" class="w-9 h-9 rounded-xl bg-black text-white flex items-center justify-center text-xs shrink-0 font-bold">
                    ➔
                </button>
            </div>

        </div>

        <button @click="csOpen = !csOpen" 
                class="w-12 h-12 rounded-2xl glass flex items-center justify-center shadow-lg hover:scale-105 transition duration-300 relative z-20 float-right">
            <span x-show="!csOpen" class="text-lg">💬</span>
            <span x-show="csOpen" x-cloak class="text-xs font-bold">✕</span>
            <span x-show="!csOpen" class="absolute -top-1 -right-1 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></span>
        </button>
        
    </div>
</div>
