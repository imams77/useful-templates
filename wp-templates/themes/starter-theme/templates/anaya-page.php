<?php
/**
 * Template Name: Anaya Page
 * Description: A custom static page template with Tailwind CSS styling.
 *
 * @package Starter_Theme
 */

get_header(); ?>

<main id="main-content" class="min-h-screen bg-white">

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-indigo-600 to-purple-700 text-white py-24 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold tracking-tight mb-6">
                <?php the_title(); ?>
            </h1>
            <?php if (has_excerpt()) : ?>
                <p class="text-lg sm:text-xl text-indigo-100 max-w-2xl mx-auto">
                    <?php echo get_the_excerpt(); ?>
                </p>sdasdasd
            <?php endif; ?>
        </div>
    </section>

    <!-- Content Section -->
    <section class="py-16 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <?php while (have_posts()) : the_post(); ?>

                <article id="post-<?php the_ID(); ?>" <?php post_class('prose prose-lg max-w-none'); ?>>

                    <?php if (has_post_thumbnail()) : ?>
                        <div class="mb-10 rounded-2xl overflow-hidden shadow-lg">
                            <?php the_post_thumbnail('large', [
                                'class' => 'w-full h-auto object-cover',
                            ]); ?>
                        </div>
                    <?php endif; ?>

                    <div class="text-gray-700 leading-relaxed">
                        <?php the_content(); ?>
                    </div>

                </article>

            <?php endwhile; ?>
        </div>
    </section>

    <!-- CTA / Feature Cards Section (static example — customize as needed) -->
    <section class="bg-gray-50 py-16 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">Features</h2>
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">

                <div class="bg-white rounded-xl shadow-sm p-8 hover:shadow-md transition-shadow duration-200">
                    <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center mb-5">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Fast Performance</h3>
                    <p class="text-gray-600">Built with Vite and Tailwind CSS for blazing fast development and optimized production builds.</p>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-8 hover:shadow-md transition-shadow duration-200">
                    <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center mb-5">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Easy Customization</h3>
                    <p class="text-gray-600">Use Tailwind utility classes directly in your PHP templates. No extra configuration needed.</p>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-8 hover:shadow-md transition-shadow duration-200">
                    <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center mb-5">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.25 6.087c0-.355.186-.676.401-.959.221-.29.349-.634.349-1.003 0-1.036-1.007-1.875-2.25-1.875s-2.25.84-2.25 1.875c0 .369.128.713.349 1.003.215.283.401.604.401.959v0a.64.64 0 01-.657.643 48.491 48.491 0 01-4.163-.3c.186 1.613.293 3.25.315 4.907a.656.656 0 01-.658.663v0c-.355 0-.676-.186-.959-.401a1.647 1.647 0 00-1.003-.349c-1.036 0-1.875 1.007-1.875 2.25s.84 2.25 1.875 2.25c.369 0 .713-.128 1.003-.349.283-.215.604-.401.959-.401v0c.31 0 .555.26.532.57a48.039 48.039 0 01-.642 5.056c1.518.19 3.058.309 4.616.354a.64.64 0 00.657-.643v0c0-.355-.186-.676-.401-.959a1.647 1.647 0 01-.349-1.003c0-1.035 1.008-1.875 2.25-1.875 1.243 0 2.25.84 2.25 1.875 0 .369-.128.713-.349 1.003-.215.283-.4.604-.4.959v0c0 .333.277.599.61.58a48.1 48.1 0 005.427-.63 48.05 48.05 0 00.582-4.717.532.532 0 00-.533-.57v0c-.355 0-.676.186-.959.401-.29.221-.634.349-1.003.349-1.035 0-1.875-1.007-1.875-2.25s.84-2.25 1.875-2.25c.37 0 .713.128 1.003.349.283.215.604.401.959.401v0a.656.656 0 00.658-.663 48.422 48.422 0 00-.37-5.36c-1.886.342-3.81.574-5.766.689a.578.578 0 01-.61-.58v0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Plugin Ready</h3>
                    <p class="text-gray-600">Structured JS boilerplate makes it simple to drop in plugins like Swiper, GSAP, or anything else.</p>
                </div>

            </div>
        </div>
    </section>

</main>

<?php get_footer(); ?>
