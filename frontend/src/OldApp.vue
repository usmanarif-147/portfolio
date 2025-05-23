<template>
  <div>
    <div class="min-h-screen bg-gray-50" v-if="resumeStore.data">
      <!-- Navigation -->
      <nav class="bg-white shadow-sm sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between h-16">
            <div class="flex items-center">
              <span class="text-xl font-bold text-indigo-600">John Doe</span>
            </div>
            <div class="hidden md:flex items-center space-x-8">
              <a href="#about" class="text-gray-700 hover:text-indigo-600 transition">About</a>
              <a href="#skills" class="text-gray-700 hover:text-indigo-600 transition">Skills</a>
              <a href="#projects" class="text-gray-700 hover:text-indigo-600 transition">Projects</a>
              <a href="#experience" class="text-gray-700 hover:text-indigo-600 transition">Experience</a>
              <a href="#contact" class="text-gray-700 hover:text-indigo-600 transition">Contact</a>
            </div>
            <div class="md:hidden flex items-center">
              <!-- Mobile menu button -->
              <button @click="isMobileMenuOpen = !isMobileMenuOpen" class="text-gray-500 hover:text-gray-900">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
              </button>
            </div>
          </div>
        </div>

        <!-- Mobile menu -->
        <div v-if="isMobileMenuOpen" class="md:hidden bg-white pb-3 px-4">
          <div class="flex flex-col space-y-2">
            <a href="#about" class="text-gray-700 hover:text-indigo-600 transition py-1">About</a>
            <a href="#skills" class="text-gray-700 hover:text-indigo-600 transition py-1">Skills</a>
            <a href="#projects" class="text-gray-700 hover:text-indigo-600 transition py-1">Projects</a>
            <a href="#experience" class="text-gray-700 hover:text-indigo-600 transition py-1">Experience</a>
            <a href="#contact" class="text-gray-700 hover:text-indigo-600 transition py-1">Contact</a>
          </div>
        </div>
      </nav>

      <main>
        <!-- Hero Section -->
        <section id="about" class="py-20 bg-gradient-to-r from-indigo-500 to-purple-600 text-white">
          <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center">
              <div class="md:w-1/2 mb-10 md:mb-0">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ resumeStore.data.profile[0].full_name }}</h1>
                <h2 class="text-2xl md:text-3xl font-semibold mb-6">{{ resumeStore.data.profile[0].title }}</h2>
                <p class="text-lg mb-8">{{ resumeStore.data.profile[0].bio }}</p>
                <div class="flex space-x-4">
                  <a :href="resumeStore.data.profile[0].github" target="_blank"
                    class="bg-white text-indigo-600 px-6 py-2 rounded-full font-medium hover:bg-gray-100 transition">
                    GitHub
                  </a>
                  <a :href="resumeStore.data.profile[0].linkedin" target="_blank"
                    class="bg-indigo-700 text-white px-6 py-2 rounded-full font-medium hover:bg-indigo-800 transition">
                    LinkedIn
                  </a>
                </div>
              </div>
              <div class="md:w-1/2 flex justify-center">
                <div class="relative">
                  <div class="w-64 h-64 bg-indigo-300 rounded-full overflow-hidden shadow-xl">
                    <!-- Placeholder for profile image -->
                    <div v-if="!resumeStore.data.profile[0].profile_image"
                      class="w-full h-full bg-indigo-200 flex items-center justify-center">
                      <span class="text-6xl text-indigo-600 font-bold">{{
                        getInitials(resumeStore.data.profile[0].full_name) }}</span>
                    </div>
                    <img v-else :src="resumeStore.data.profile[0].profile_image"
                      :alt="resumeStore.data.profile[0].full_name" class="w-full h-full object-cover">
                  </div>
                  <div class="absolute -bottom-4 -right-4 bg-white text-gray-800 py-2 px-4 rounded-full shadow-md">
                    <span class="font-medium">📍 {{ resumeStore.data.profile[0].location }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- Skills Section -->
        <section id="skills" class="py-20 bg-white">
          <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
              <h2 class="text-3xl font-bold text-gray-900 mb-4">My Skills</h2>
              <div class="w-20 h-1 bg-indigo-600 mx-auto"></div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
              <div v-for="skill in resumeStore.data.skills" :key="skill.id"
                class="bg-gray-50 p-6 rounded-lg shadow-sm hover:shadow-md transition">
                <div class="flex items-center mb-3">
                  <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center mr-3">
                    <span class="text-indigo-600 font-bold">{{ skill.name.charAt(0) }}</span>
                  </div>
                  <h3 class="text-lg font-medium text-gray-900">{{ skill.name }}</h3>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                  <div class="bg-indigo-600 h-2 rounded-full" :style="{ width: getSkillWidth(skill.level) }"></div>
                </div>
                <p class="mt-2 text-sm text-gray-500 capitalize">{{ skill.level }}</p>
              </div>
            </div>
          </div>
        </section>

        <!-- Projects Section -->
        <section id="projects" class="py-20 bg-gray-50">
          <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
              <h2 class="text-3xl font-bold text-gray-900 mb-4">Featured Projects</h2>
              <div class="w-20 h-1 bg-indigo-600 mx-auto"></div>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
              <div v-for="project in resumeStore.data.projects" :key="project.id"
                class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
                <div class="h-48 bg-indigo-100 flex items-center justify-center">
                  <span class="text-4xl text-indigo-600 font-bold">{{ project.title.charAt(0) }}</span>
                </div>
                <div class="p-6">
                  <h3 class="text-xl font-bold text-gray-900 mb-2">{{ project.title }}</h3>
                  <p class="text-gray-600 mb-4">{{ project.description }}</p>

                  <div class="flex flex-wrap gap-2 mb-4">
                    <span v-for="(tech, index) in JSON.parse(project.tech_stack)" :key="index"
                      class="bg-indigo-100 text-indigo-800 text-xs px-3 py-1 rounded-full">
                      {{ tech }}
                    </span>
                  </div>

                  <div class="flex space-x-3">
                    <a :href="project.github_url" target="_blank"
                      class="text-indigo-600 hover:text-indigo-800 font-medium flex items-center">
                      <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill-rule="evenodd"
                          d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"
                          clip-rule="evenodd" />
                      </svg>
                      Code
                    </a>
                    <a v-if="project.project_url" :href="project.project_url" target="_blank"
                      class="text-indigo-600 hover:text-indigo-800 font-medium flex items-center">
                      <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                      </svg>
                      Live Demo
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- Experience & Education Section -->
        <section id="experience" class="py-20 bg-white">
          <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12">
              <!-- Experience -->
              <div>
                <div class="text-center mb-10">
                  <h2 class="text-3xl font-bold text-gray-900 mb-4">Work Experience</h2>
                  <div class="w-20 h-1 bg-indigo-600 mx-auto"></div>
                </div>

                <div class="space-y-8">
                  <div v-for="exp in resumeStore.data.experience" :key="exp.id"
                    class="relative pl-10 pb-8 border-l-2 border-indigo-200">
                    <div class="absolute -left-2 top-0 w-4 h-4 rounded-full bg-indigo-600"></div>
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-2">
                      <h3 class="text-lg font-bold text-gray-900">{{ exp.position }}</h3>
                      <span class="text-sm text-indigo-600 font-medium">
                        {{ formatDate(exp.start_date) }} - {{ exp.is_current ? 'Present' : formatDate(exp.end_date) }}
                      </span>
                    </div>
                    <h4 class="text-gray-600 font-medium mb-2">{{ exp.company_name }} • {{ exp.location }}</h4>
                    <p class="text-gray-600">{{ exp.description }}</p>
                  </div>
                </div>
              </div>

              <!-- Education -->
              <div>
                <div class="text-center mb-10">
                  <h2 class="text-3xl font-bold text-gray-900 mb-4">Education</h2>
                  <div class="w-20 h-1 bg-indigo-600 mx-auto"></div>
                </div>

                <div class="space-y-8">
                  <div v-for="edu in resumeStore.data.education" :key="edu.id"
                    class="relative pl-10 pb-8 border-l-2 border-indigo-200">
                    <div class="absolute -left-2 top-0 w-4 h-4 rounded-full bg-indigo-600"></div>
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-2">
                      <h3 class="text-lg font-bold text-gray-900">{{ edu.degree }}</h3>
                      <span class="text-sm text-indigo-600 font-medium">
                        {{ edu.start_year }} - {{ edu.end_year }}
                      </span>
                    </div>
                    <h4 class="text-gray-600 font-medium mb-2">{{ edu.institution }} • {{ edu.field_of_study }}</h4>
                    <p class="text-gray-600">{{ edu.description }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- Contact Section -->
        <section id="contact" class="py-20 bg-gray-50">
          <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
              <h2 class="text-3xl font-bold text-gray-900 mb-4">Get In Touch</h2>
              <div class="w-20 h-1 bg-indigo-600 mx-auto"></div>
            </div>

            <div class="grid md:grid-cols-2 gap-12">
              <div>
                <h3 class="text-xl font-bold text-gray-900 mb-6">Contact Information</h3>
                <div class="space-y-4">
                  <div class="flex items-start">
                    <div class="flex-shrink-0 bg-indigo-100 p-3 rounded-lg">
                      <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                      </svg>
                    </div>
                    <div class="ml-4">
                      <h4 class="text-sm font-medium text-gray-500">Email</h4>
                      <a :href="'mailto:' + resumeStore.data.profile[0].email"
                        class="text-base text-gray-900 hover:text-indigo-600">{{ resumeStore.data.profile[0].email
                        }}</a>
                    </div>
                  </div>

                  <div class="flex items-start">
                    <div class="flex-shrink-0 bg-indigo-100 p-3 rounded-lg">
                      <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                      </svg>
                    </div>
                    <div class="ml-4">
                      <h4 class="text-sm font-medium text-gray-500">Phone</h4>
                      <a :href="'tel:' + resumeStore.data.profile[0].phone"
                        class="text-base text-gray-900 hover:text-indigo-600">{{ resumeStore.data.profile[0].phone
                        }}</a>
                    </div>
                  </div>

                  <div class="flex items-start">
                    <div class="flex-shrink-0 bg-indigo-100 p-3 rounded-lg">
                      <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                      </svg>
                    </div>
                    <div class="ml-4">
                      <h4 class="text-sm font-medium text-gray-500">Location</h4>
                      <p class="text-base text-gray-900">{{ resumeStore.data.profile[0].location }}</p>
                    </div>
                  </div>
                </div>

                <div class="mt-8">
                  <h3 class="text-xl font-bold text-gray-900 mb-4">Connect With Me</h3>
                  <div class="flex space-x-4">
                    <a :href="resumeStore.data.profile[0].linkedin" target="_blank"
                      class="bg-gray-100 p-3 rounded-lg hover:bg-indigo-100 transition">
                      <svg class="w-6 h-6 text-gray-700 hover:text-indigo-600" fill="currentColor" viewBox="0 0 24 24">
                        <path
                          d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" />
                      </svg>
                    </a>
                    <a :href="resumeStore.data.profile[0].github" target="_blank"
                      class="bg-gray-100 p-3 rounded-lg hover:bg-indigo-100 transition">
                      <svg class="w-6 h-6 text-gray-700 hover:text-indigo-600" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd"
                          d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"
                          clip-rule="evenodd" />
                      </svg>
                    </a>
                  </div>
                </div>
              </div>

              <!-- Contact Form -->
              <div>
                <form @submit.prevent="submitForm" class="bg-white p-6 rounded-xl shadow-sm">
                  <h3 class="text-xl font-bold text-gray-900 mb-6">Send Me a Message</h3>

                  <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Your Name</label>
                    <input type="text" id="name" v-model="form.name" required
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                  </div>

                  <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Your Email</label>
                    <input type="email" id="email" v-model="form.email" required
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                  </div>

                  <div class="mb-4">
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                    <input type="text" id="subject" v-model="form.subject" required
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                  </div>

                  <div class="mb-6">
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                    <textarea id="message" rows="4" v-model="form.message" required
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                  </div>

                  <button type="submit"
                    class="w-full bg-indigo-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-indigo-700 transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Send Message
                  </button>
                </form>
              </div>
            </div>
          </div>
        </section>
      </main>

      <!-- Footer -->
      <footer class="bg-gray-900 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex flex-col md:flex-row justify-between items-center">
            <div class="mb-4 md:mb-0">
              <p>&copy; {{ new Date().getFullYear() }} {{ resumeStore.data.profile[0].full_name }}. All rights reserved.
              </p>
            </div>
            <div class="flex space-x-6">
              <a :href="resumeStore.data.profile[0].github" target="_blank" class="text-gray-400 hover:text-white">
                <span class="sr-only">GitHub</span>
                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                  <path fill-rule="evenodd"
                    d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"
                    clip-rule="evenodd" />
                </svg>
              </a>
              <a :href="resumeStore.data.profile[0].linkedin" target="_blank" class="text-gray-400 hover:text-white">
                <span class="sr-only">LinkedIn</span>
                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                  <path
                    d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" />
                </svg>
              </a>
            </div>
          </div>
        </div>
      </footer>
    </div>
    <div v-else>
      loading
    </div>
  </div>


</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useResumeStore } from './store/resume.store';

const resumeStore = useResumeStore();
const isMobileMenuOpen = ref(false);

const form = ref({
  name: '',
  email: '',
  subject: '',
  message: ''
});

const submitForm = () => {
  // Here you would typically send the form data to your backend
  console.log('Form submitted:', form.value);
  alert('Thank you for your message! I will get back to you soon.');

  // Reset form
  form.value = {
    name: '',
    email: '',
    subject: '',
    message: ''
  };
};

const getInitials = (name) => {
  return name.split(' ').map(n => n[0]).join('');
};

const getSkillWidth = (level) => {
  const levels = {
    'beginner': '33%',
    'intermediate': '66%',
    'advanced': '100%',
    'expert': '100%'
  };
  return levels[level.toLowerCase()] || '50%';
};

const formatDate = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
};

onMounted(async () => {
  await resumeStore.getPortfolio();
});
</script>

<style>
/* Smooth scrolling */
html {
  scroll-behavior: smooth;
}
</style>