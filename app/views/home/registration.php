<?php 
$pageTitle = 'Register Your Child';
ob_start(); 
?>

<!-- Page Header -->
<section class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-16">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl md:text-5xl font-bold text-center">Register Your Child</h1>
        <p class="text-center text-purple-100 mt-4 text-lg">Take the first step towards your child's educational journey</p>
    </div>
</section>

<!-- Registration Form Section -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-xl shadow-lg p-8">
                <div class="bg-purple-50 border-l-4 border-purple-600 p-4 mb-8">
                    <p class="text-sm text-gray-700">
                        <strong>Registration Process:</strong> Fill out the form below and click submit. You will be redirected to WhatsApp to complete your registration and schedule a visit.
                    </p>
                </div>
                
                <form action="<?= url('/registration') ?>" method="POST" class="space-y-6">
                    <?= csrf_field() ?>
                    
                    <?php if (isset($formFields) && !empty($formFields)): ?>
                        <?php foreach ($formFields as $field): ?>
                            <div>
                                <label for="<?= e($field['field_name']) ?>" class="block text-sm font-medium text-gray-700 mb-2">
                                    <?= e($field['field_label']) ?> 
                                    <?php if ($field['is_required']): ?>
                                        <span class="text-red-500">*</span>
                                    <?php endif; ?>
                                </label>
                                
                                <?php if ($field['field_type'] === 'textarea'): ?>
                                    <textarea 
                                        id="<?= e($field['field_name']) ?>" 
                                        name="<?= e($field['field_name']) ?>" 
                                        rows="4"
                                        <?= $field['is_required'] ? 'required' : '' ?>
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent"
                                    ><?= e(old($field['field_name'])) ?></textarea>
                                <?php else: ?>
                                    <input 
                                        type="<?= e($field['field_type']) ?>" 
                                        id="<?= e($field['field_name']) ?>" 
                                        name="<?= e($field['field_name']) ?>"
                                        value="<?= e(old($field['field_name'])) ?>"
                                        <?= $field['is_required'] ? 'required' : '' ?>
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent"
                                    >
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Default form fields if custom fields not configured -->
                        <div>
                            <label for="child_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Child's Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="child_name" name="child_name" value="<?= e(old('child_name')) ?>" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label for="parent_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Parent's Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="parent_name" name="parent_name" value="<?= e(old('parent_name')) ?>" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="email" name="email" value="<?= e(old('email')) ?>" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Phone Number <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" id="phone" name="phone" value="<?= e(old('phone')) ?>" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                Address
                            </label>
                            <textarea id="address" name="address" rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent"><?= e(old('address')) ?></textarea>
                        </div>
                        
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                                Message/Notes
                            </label>
                            <textarea id="message" name="message" rows="4"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent"><?= e(old('message')) ?></textarea>
                        </div>
                    <?php endif; ?>
                    
                    <div class="pt-4">
                        <button type="submit" class="w-full bg-purple-600 text-white px-8 py-4 rounded-lg font-bold text-lg hover:bg-purple-700 transition">
                            Submit & Continue to WhatsApp
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Additional Information -->
            <div class="mt-8 bg-gray-50 rounded-xl p-6">
                <h3 class="text-xl font-bold mb-4">What Happens Next?</h3>
                <ol class="space-y-3 text-gray-700">
                    <li class="flex items-start">
                        <span class="bg-purple-600 text-white rounded-full w-6 h-6 flex items-center justify-center flex-shrink-0 mr-3 mt-0.5">1</span>
                        <span>Submit the registration form above</span>
                    </li>
                    <li class="flex items-start">
                        <span class="bg-purple-600 text-white rounded-full w-6 h-6 flex items-center justify-center flex-shrink-0 mr-3 mt-0.5">2</span>
                        <span>You'll be redirected to WhatsApp to chat with our admission team</span>
                    </li>
                    <li class="flex items-start">
                        <span class="bg-purple-600 text-white rounded-full w-6 h-6 flex items-center justify-center flex-shrink-0 mr-3 mt-0.5">3</span>
                        <span>Schedule a visit to our school and meet our teachers</span>
                    </li>
                    <li class="flex items-start">
                        <span class="bg-purple-600 text-white rounded-full w-6 h-6 flex items-center justify-center flex-shrink-0 mr-3 mt-0.5">4</span>
                        <span>Complete the enrollment process and welcome to our family!</span>
                    </li>
                </ol>
            </div>
        </div>
    </div>
</section>

<?php 
$content = ob_get_clean();
require VIEW_PATH . '/layouts/main.php';
?>
