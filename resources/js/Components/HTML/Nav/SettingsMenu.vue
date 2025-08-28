<script setup>

import UpdateCaretakerRecruiters from '@/Components/Dialogs/UpdateCaretakerRecruiters.vue';
import MimicUserMenu from '@/Components/Admin/MimicUserMenu.vue';

</script>

<template>
    <v-menu location="bottom" v-if="$page.props.god_mode || $page.props.is_super_admin" open-on-hover>
        <template v-slot:activator="{ props }">
            <v-btn color="#f1f5f9" rounded="0" v-bind="props" size="small" variant="text">
                <i class="fa-solid fa-gear"></i>
            </v-btn>
        </template>
        <v-list>
            <MimicUserMenu />

            <v-list-item link>
                <v-list-item-title>Opiekunki</v-list-item-title>
                <template v-slot:append>
                    <v-icon icon="mdi-menu-right" size="x-small"></v-icon>
                </template>
                <v-menu :open-on-focus="false" activator="parent" open-on-hover submenu>
                    <v-list>
                        <UpdateCaretakerRecruiters />
                        <v-list-item link>
                            <v-list-item-title>Aktualizuj punkty</v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-menu>
            </v-list-item>
            <v-list-item link>
                <v-list-item-title>Wypłaty</v-list-item-title>
                <template v-slot:append>
                    <v-icon icon="mdi-menu-right" size="x-small"></v-icon>
                </template>
                <v-menu :open-on-focus="false" activator="parent" open-on-hover submenu>
                    <v-list>
                        <v-list-item link>
                            <v-list-item-title>Generuj plik z wypłatami</v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-menu>
            </v-list-item>
        </v-list>
    </v-menu>
</template>