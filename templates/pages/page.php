<?php
/*
Template Name: Default Page Template
*/
?>

<?php get_header();?>

<main id="primary-page-template" class="container ">
    <div class="mb-10 space-y-10">
        <div>
            <h1>Heading 1</h1>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos.</p>
            <button>Button</button>
        </div>

        <div>
            <h2>Heading 2</h2>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos.</p>
            <button>Button</button>
        </div>
        <div>
            <h3>Heading 3</h3>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos.</p>
            <button>Button</button>
        </div>
        <div>
            <h4>Heading 4</h4>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos.</p>
            <button>Button</button>
        </div>

        <div>
            <h5>Heading 5</h5>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos.</p>
            <button>Button</button>
        </div>
    </div>

    <div class="mb-10">
        <h2 class="mb-10">Logo Carousel</h2>
        <?php get_template_part('templates/modules/logo-carousel'); ?>
    </div>

</main>

<?php get_footer();?>