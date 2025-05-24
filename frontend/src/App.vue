<template>
  <div class="min-h-screen bg-gray-50" v-if="resumeStore.data">
    <!-- Navigation -->
    <nav class="bg-white shadow-md fixed w-full z-10">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <span class="text-xl font-bold text-indigo-600">{{ resumeStore.data.profile[0]?.full_name || 'My Portfolio'
            }}</span>
          </div>
          <div class="hidden md:flex space-x-8 items-center">
            <a href="#about" class="text-gray-700 hover:text-indigo-600 px-3 py-2 text-sm font-medium">About Me</a>
            <a href="#experience"
              class="text-gray-700 hover:text-indigo-600 px-3 py-2 text-sm font-medium">Experience</a>
            <a href="#education" class="text-gray-700 hover:text-indigo-600 px-3 py-2 text-sm font-medium">Education</a>
            <a href="#skills" class="text-gray-700 hover:text-indigo-600 px-3 py-2 text-sm font-medium">Skills</a>
            <a href="#projects" class="text-gray-700 hover:text-indigo-600 px-3 py-2 text-sm font-medium">Projects</a>
            <a href="#contact"
              class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Contact</a>
          </div>
          <div class="md:hidden flex items-center">
            <button @click="mobileMenuOpen = !mobileMenuOpen"
              class="text-gray-500 hover:text-gray-700 focus:outline-none">
              <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path v-if="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16" />
                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
      </div>
      <!-- Mobile menu -->
      <div v-show="mobileMenuOpen" class="md:hidden bg-white shadow-md">
        <div class="px-2 pt-2 pb-3 space-y-1">
          <a href="#about" @click="mobileMenuOpen = false"
            class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50">About</a>
          <a href="#experience" @click="mobileMenuOpen = false"
            class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50">Experience</a>
          <a href="#education" @click="mobileMenuOpen = false"
            class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50">Education</a>
          <a href="#skills" @click="mobileMenuOpen = false"
            class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50">Skills</a>
          <a href="#projects" @click="mobileMenuOpen = false"
            class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50">Projects</a>
          <a href="#contact" @click="mobileMenuOpen = false"
            class="block px-3 py-2 rounded-md text-base font-medium text-indigo-600">Contact</a>
        </div>
      </div>
    </nav>

    <!-- Hero Section -->
    <header class="pt-24 pb-12 bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row items-center">
          <div class="md:w-1/2 mb-8 md:mb-0 text-center md:text-left">
            <h1 class="text-4xl sm:text-5xl font-bold mb-4">
              {{ resumeStore.data.profile[0]?.full_name || 'John Doe' }}
            </h1>
            <h2 class="text-xl sm:text-2xl mb-6">{{ resumeStore.data.profile[0]?.title || 'Full Stack Developer' }}</h2>
            <p class="text-lg opacity-90 mb-8">{{ resumeStore.data.profile[0]?.bio || 'Passionate developer with' }}</p>
            <div class="flex justify-center md:justify-start space-x-4">
              <a v-if="resumeStore.data.profile[0]?.github" :href="resumeStore.data.profile[0].github" target="_blank"
                class="bg-white text-indigo-700 px-6 py-2 rounded-md font-medium hover:bg-gray-100">GitHub</a>
              <a v-if="resumeStore.data.profile[0]?.linkedin" :href="resumeStore.data.profile[0].linkedin"
                target="_blank"
                class="bg-transparent border border-white text-white px-6 py-2 rounded-md font-medium hover:bg-white hover:text-indigo-700">LinkedIn</a>
            </div>
          </div>
          <div class="md:w-1/2 flex justify-center md:justify-end">
            <div class="w-48 h-48 rounded-full bg-white/20 flex items-center justify-center shadow-lg">
              <img v-if="resumeStore.data.profile[0]?.profile_image" :src="resumeStore.data.profile[0].profile_image"
                alt="Profile" class="w-44 h-44 rounded-full object-cover" />
              <span v-else class="text-6xl font-bold">{{ getInitials(resumeStore.data.profile[0]?.full_name) }}</span>
            </div>
          </div>
        </div>
      </div>
    </header>

    <main class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <!-- About Section -->
      <section id="about" class="mb-16">
        <h2 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2">About Me</h2>
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex flex-col md:flex-row">
            <div class="w-full md:w-2/3 mb-6 md:mb-0 md:pr-6">
              <p class="text-gray-700 mb-4">{{ resumeStore.data.profile[0]?.bio || 'No bio information available.' }}
              </p>
              <p class="text-gray-700">I'm a dedicated developer passionate about creating elegant solutions to complex
                problems. With a focus on clean code and user experience, I strive to build applications that make a
                difference.</p>
            </div>
            <div class="w-full md:w-1/3 bg-gray-50 p-4 rounded-lg">
              <h3 class="font-semibold text-gray-800 mb-4">Contact Information</h3>
              <div class="space-y-3">
                <div v-if="resumeStore.data.profile[0]?.email" class="flex items-center">
                  <svg class="h-5 w-5 text-indigo-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                  </svg>
                  <span class="text-gray-700">{{ resumeStore.data.profile[0].email }}</span>
                </div>
                <div v-if="resumeStore.data.profile[0]?.phone" class="flex items-center">
                  <svg class="h-5 w-5 text-indigo-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                  </svg>
                  <span class="text-gray-700">{{ resumeStore.data.profile[0].phone }}</span>
                </div>
                <div v-if="resumeStore.data.profile[0]?.location" class="flex items-center">
                  <svg class="h-5 w-5 text-indigo-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                  <span class="text-gray-700">{{ resumeStore.data.profile[0].location }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Experience Section -->
      <section id="experience" class="mb-16">
        <h2 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2">Work Experience</h2>
        <div class="space-y-6">
          <div v-for="(exp, index) in resumeStore.data.experience" :key="index" class="bg-white rounded-lg shadow p-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-4">
              <div>
                <h3 class="text-xl font-semibold text-gray-800">{{ exp.position }}</h3>
                <p class="text-indigo-600 font-medium">{{ exp.company_name }}</p>
              </div>
              <div class="text-gray-600 mt-2 md:mt-0">
                <span>{{ formatDate(exp.start_date) }} - {{ exp.is_current ? 'Present' : formatDate(exp.end_date)
                }}</span>
                <p>{{ exp.location }}</p>
              </div>
            </div>
            <p class="text-gray-700">{{ exp.description }}</p>
          </div>
          <div v-if="!resumeStore.data.experience || resumeStore.data.experience.length === 0"
            class="bg-white rounded-lg shadow p-6 text-center text-gray-500">
            No work experience added yet.
          </div>
        </div>
      </section>

      <!-- Education Section -->
      <section id="education" class="mb-16">
        <h2 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2">Education</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div v-for="(edu, index) in resumeStore.data.education" :key="index" class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-start mb-3">
              <div>
                <h3 class="text-xl font-semibold text-gray-800">{{ edu.institution }}</h3>
                <p class="text-indigo-600">{{ edu.degree }}</p>
              </div>
              <span class="text-gray-600">{{ edu.start_year }} - {{ edu.end_year }}</span>
            </div>
            <p class="text-gray-700 font-medium">{{ edu.field_of_study }}</p>
            <p v-if="edu.description" class="text-gray-600 mt-2">{{ edu.description }}</p>
          </div>
          <div v-if="!resumeStore.data.education || resumeStore.data.education.length === 0"
            class="bg-white rounded-lg shadow p-6 text-center text-gray-500">
            No education added yet.
          </div>
        </div>
      </section>

      <!-- Skills Section -->
      <section id="skills" class="mb-16">
        <h2 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2">Skills</h2>
        <div class="bg-white rounded-lg shadow p-6">
          <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
            <div v-for="(skill, index) in resumeStore.data.skills" :key="index"
              class="bg-gray-50 rounded-lg p-4 flex flex-col items-center text-center">
              <div class="text-indigo-600 font-semibold mb-2">{{ skill.name }}</div>
              <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-indigo-600 h-2 rounded-full" :style="getSkillLevelWidth(skill.level)"></div>
              </div>
              <span class="text-xs text-gray-600 mt-1">{{ skill.level }}</span>
            </div>
          </div>
          <div v-if="!resumeStore.data.skills || resumeStore.data.skills.length === 0"
            class="text-center text-gray-500 py-4">
            No skills added yet.
          </div>
        </div>
      </section>

      <!-- Projects Section -->
      <section id="projects" class="mb-16">
        <h2 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2">Projects</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div v-for="(project, index) in resumeStore.data.projects" :key="index"
            class="bg-white rounded-lg shadow overflow-hidden">
            <div class="h-48 bg-gray-200 flex items-center justify-center">
              <img v-if="getProjectImages(project.images).length > 0" :src="getProjectImages(project.images)[0]"
                alt="Project thumbnail" class="w-full h-full object-cover" />
              <div v-else class="text-gray-400 flex items-center justify-center h-full w-full">
                <svg class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
              </div>
            </div>
            <div class="p-6">
              <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ project.title }}</h3>
              <p class="text-gray-600 mb-4">{{ project.description }}</p>
              <div class="flex flex-wrap gap-2 mb-4">
                <span v-for="(tech, techIndex) in getProjectTechStack(project.tech_stack)" :key="techIndex"
                  class="bg-indigo-100 text-indigo-800 text-xs px-2 py-1 rounded">{{ tech }}</span>
              </div>
              <div class="flex space-x-4">
                <a v-if="project.project_url" :href="project.project_url" target="_blank"
                  class="text-indigo-600 hover:text-indigo-800 font-medium flex items-center">
                  <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                  </svg>
                  Live Demo
                </a>
                <a v-if="project.github_url" :href="project.github_url" target="_blank"
                  class="text-gray-600 hover:text-gray-800 font-medium flex items-center">
                  <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd"
                      d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"
                      clip-rule="evenodd" />
                  </svg>
                  Source Code
                </a>
              </div>
            </div>
          </div>
          <div v-if="!resumeStore.data.projects || resumeStore.data.projects.length === 0"
            class="bg-white rounded-lg shadow p-6 text-center text-gray-500">
            No projects added yet.
          </div>
        </div>
      </section>

      <!-- Contact Section -->
      <section id="contact" class="mb-16">
        <h2 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2">Get In Touch</h2>
        <div class="bg-white rounded-lg shadow p-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
              <h3 class="text-xl font-semibold text-gray-800 mb-4">Contact Information</h3>
              <div class="space-y-4">
                <div v-if="resumeStore.data.profile[0]?.email" class="flex items-center">
                  <div class="bg-indigo-100 p-3 rounded-full mr-4">
                    <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                  </div>
                  <div>
                    <p class="text-sm text-gray-600">Email</p>
                    <p class="text-gray-800">{{ resumeStore.data.profile[0].email }}</p>
                  </div>
                </div>
                <div v-if="resumeStore.data.profile[0]?.phone" class="flex items-center">
                  <div class="bg-indigo-100 p-3 rounded-full mr-4">
                    <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                  </div>
                  <div>
                    <p class="text-sm text-gray-600">Phone</p>
                    <p class="text-gray-800">{{ resumeStore.data.profile[0].phone }}</p>
                  </div>
                </div>
                <div v-if="resumeStore.data.profile[0]?.location" class="flex items-center">
                  <div class="bg-indigo-100 p-3 rounded-full mr-4">
                    <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                  </div>
                  <div>
                    <p class="text-sm text-gray-600">Location</p>
                    <p class="text-gray-800">{{ resumeStore.data.profile[0].location }}</p>
                  </div>
                </div>
                <div class="flex items-center space-x-4 mt-6">
                  <a v-if="resumeStore.data.profile[0]?.github" :href="resumeStore.data.profile[0].github"
                    target="_blank" class="text-gray-600 hover:text-gray-900">
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                      <path fill-rule="evenodd"
                        d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"
                        clip-rule="evenodd" />
                    </svg>
                  </a>
                  <a v-if="resumeStore.data.profile[0]?.linkedin" :href="resumeStore.data.profile[0].linkedin"
                    target="_blank" class="text-gray-600 hover:text-gray-900">
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                      <path
                        d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                    </svg>
                  </a>
                </div>
              </div>
            </div>
            <div>
              <h3 class="text-xl font-semibold text-gray-800 mb-4">Send Me a Message</h3>
              <form @submit.prevent="submitContactForm" class="space-y-4">
                <div>
                  <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Your Name</label>
                  <input type="text" id="name" v-model="contactForm.name"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                    required />
                </div>
                <div>
                  <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Your Email</label>
                  <input type="email" id="email" v-model="contactForm.email"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                    required />
                </div>
                <div>
                  <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                  <input type="text" id="subject" v-model="contactForm.subject"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                    required />
                </div>
                <div class="flex items-center justify-between">
                  <button type="submit"
                    class="bg-indigo-600 text-white py-2 px-6 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Send Message
                  </button>
                  <div v-if="formSubmitting" class="text-gray-600">Sending...</div>
                  <div v-if="formSubmitted" class="text-green-600">Message sent!</div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </section>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-center">
          <div class="mb-4 md:mb-0">
            <h3 class="text-xl font-bold">{{ resumeStore.data.profile[0]?.full_name || 'My Portfolio' }}</h3>
            <p class="text-gray-400">{{ resumeStore.data.profile[0]?.title || 'Full Stack Developer' }}</p>
          </div>
          <div class="flex space-x-6">
            <a v-if="resumeStore.data.profile[0]?.github" :href="resumeStore.data.profile[0].github" target="_blank"
              class="text-gray-400 hover:text-white">
              <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd"
                  d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"
                  clip-rule="evenodd" />
              </svg>
            </a>
            <a v-if="resumeStore.data.profile[0]?.linkedin" :href="resumeStore.data.profile[0].linkedin" target="_blank"
              class="text-gray-400 hover:text-white">
              <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                <path
                  d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
              </svg>
            </a>
          </div>
        </div>
        <div class="mt-8 text-center text-gray-400 text-sm">
          <p>&copy; {{ new Date().getFullYear() }} {{ resumeStore.data.profile[0]?.full_name || 'My Portfolio' }}. All
            rights
            reserved.</p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref, onMounted, reactive } from 'vue';
import { useResumeStore } from './store/resume.store';

const resumeStore = useResumeStore();
const mobileMenuOpen = ref(false);
const formSubmitting = ref(false);
const formSubmitted = ref(false);

const contactForm = reactive({
  name: '',
  email: '',
  subject: '',
  message: ''
});

onMounted(async () => {
  await resumeStore.getPortfolio();
});

// Helper functions
const getInitials = (name) => {
  if (!name) return 'JD';
  return name
    .split(' ')
    .map(part => part[0])
    .join('')
    .toUpperCase()
    .substring(0, 2);
};

const formatDate = (dateString) => {
  if (!dateString) return 'N/A';
  const options = { year: 'numeric', month: 'short' };
  return new Date(dateString).toLocaleDateString(undefined, options);
};

const getSkillLevelWidth = (level) => {
  const levels = {
    'Beginner': '25%',
    'Intermediate': '50%',
    'Advanced': '75%',
    'Expert': '100%'
  };
  return { width: levels[level] || '25%' };
};

const getProjectImages = (imagesStr) => {
  try {
    if (!imagesStr || imagesStr === '[]') return [];
    return JSON.parse(imagesStr);
  } catch {
    return [];
  }
};

const getProjectTechStack = (techStackStr) => {
  try {
    if (!techStackStr || techStackStr === '[]') return [];
    return JSON.parse(techStackStr);
  } catch {
    return [];
  }
};

const submitContactForm = async () => {
  // In a real application, you would send the form data to a backend API
  formSubmitting.value = true;

  // Simulate API call with a delay
  await new Promise(resolve => setTimeout(resolve, 1000));

  // Reset the form after successful submission
  contactForm.name = '';
  contactForm.email = '';
  contactForm.subject = '';
  contactForm.message = '';

  formSubmitting.value = false;
  formSubmitted.value = true;

  // Reset the success message after a delay
  setTimeout(() => {
    formSubmitted.value = false;
  }, 3000);
};
</script>

<style>
html {
  scroll-behavior: smooth;
}

body {
  font-family: 'Inter', sans-serif;
}
</style>