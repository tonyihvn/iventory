@extends('template')
@section('content')
<div class="container">
    <div class="row">
        <div class="card col m6 offset-m3" style="margin-top:20px;">
            
                <h3 class="card-header text-center" style="text-align:center;">System Setup</h3>
                
                    <form method="POST" action="{{ route('settings.store') }}" enctype="multipart/form-data" >
                        @csrf

                        <div class="input-field">
                                <input id="organization_name" type="text" class="validate" name="organization_name" required autofocus>
                                <label for="organization_name">Organization Name</label>
                        </div>

                        <div class="input-field">
                                <textarea id="description" class="materialize-textarea" name="description"></textarea>                         
                                <label for="description" >Description</label>
                        </div>

                        <div class="file-field input-field">
                            <div class="btn">
                                <span>Upload Logo</span>
                                <input type="file" name="logo">
                            </div>
                            <div class="file-path-wrapper">
                                <input class="file-path validate" type="text">
                            </div>
                        </div>                        
                        

                        <div class="input-field">
                                <input id="address" type="text" class="validate" name="address" required>
                                <label for="address">Address</label>
                        </div>

                        <div class="input-field">
                                <input id="phone_number" type="text" class="validate" name="phone_number" required>
                                <label for="phone_number">Phone Number</label>
                        </div>

                        <div class="input-field">
                                <input id="copyright" type="text" class="validate" name="copyright" required>
                                <label for="copyright">Copyright Text / Info</label>
                        </div>

                        <div style="margin-top: 30px; padding: 15px; background-color: #f5f5f5; border-radius: 4px;">
                            <div class="switch" style="margin-bottom: 10px;">
                                <label style="font-size: 14px;">
                                    <input type="checkbox" name="disable_pagination" value="1" 
                                        {{ isset($settings) && $settings->disable_pagination ? 'checked' : '' }} />
                                    <span class="lever"></span>
                                    Disable Pagination
                                </label>
                            </div>
                            <p style="font-size: 12px; color: #999; margin: 5px 0 0 0;">Display all records on one page (Inventories) - When enabled, all inventory records will be displayed without pagination. Disable this for large datasets to improve performance.</p>
                        </div>

                        <!-- ======== SITE BUILDER SECTION ======== -->
                        <hr style="margin-top: 30px; margin-bottom: 30px;">
                        <h4 style="text-align: center; color: #1976D2; margin-bottom: 20px;">🎨 Site Builder - Customize Your App</h4>

                        <!-- Theme Mode -->
                        <div class="input-field">
                            <select name="theme_mode" id="theme_mode">
                                <option value="light" {{ isset($settings) && $settings->theme_mode == 'light' ? 'selected' : '' }}>Light Theme</option>
                                <option value="dark" {{ isset($settings) && $settings->theme_mode == 'dark' ? 'selected' : '' }}>Dark Theme</option>
                            </select>
                            <label for="theme_mode">Theme Mode</label>
                        </div>

                        <!-- COLORS SECTION -->
                        <div style="background-color: #f9f9f9; padding: 15px; border-radius: 4px; margin-top: 20px;">
                            <h5 style="color: #1976D2; margin-top: 0;">🎯 Color Scheme</h5>
                            
                            <div class="row">
                                <div class="col s6">
                                    <label style="display: block; margin-bottom: 10px; font-weight: bold;">Primary Color</label>
                                    <input type="color" name="primary_color" value="{{ isset($settings) && $settings->primary_color ? $settings->primary_color : '#2196F3' }}" style="width: 100%; height: 50px; border: 1px solid #ddd; cursor: pointer;">
                                    <p style="font-size: 12px; color: #999; margin-top: 5px;">Main brand color used throughout the app</p>
                                </div>
                                <div class="col s6">
                                    <label style="display: block; margin-bottom: 10px; font-weight: bold;">Secondary Color</label>
                                    <input type="color" name="secondary_color" value="{{ isset($settings) && $settings->secondary_color ? $settings->secondary_color : '#FFC107' }}" style="width: 100%; height: 50px; border: 1px solid #ddd; cursor: pointer;">
                                    <p style="font-size: 12px; color: #999; margin-top: 5px;">Accent color for highlights</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col s6">
                                    <label style="display: block; margin-bottom: 10px; font-weight: bold;">Background Color</label>
                                    <input type="color" name="background_color" value="{{ isset($settings) && $settings->background_color ? $settings->background_color : '#FFFFFF' }}" style="width: 100%; height: 50px; border: 1px solid #ddd; cursor: pointer;">
                                    <p style="font-size: 12px; color: #999; margin-top: 5px;">Main page background color</p>
                                </div>
                                <div class="col s6">
                                    <label style="display: block; margin-bottom: 10px; font-weight: bold;">Sidebar Color</label>
                                    <input type="color" name="sidebar_color" value="{{ isset($settings) && $settings->sidebar_color ? $settings->sidebar_color : '#263238' }}" style="width: 100%; height: 50px; border: 1px solid #ddd; cursor: pointer;">
                                    <p style="font-size: 12px; color: #999; margin-top: 5px;">Navigation sidebar background</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col s6">
                                    <label style="display: block; margin-bottom: 10px; font-weight: bold;">Top Bar Color</label>
                                    <input type="color" name="topbar_color" value="{{ isset($settings) && $settings->topbar_color ? $settings->topbar_color : '#009688' }}" style="width: 100%; height: 50px; border: 1px solid #ddd; cursor: pointer;">
                                    <p style="font-size: 12px; color: #999; margin-top: 5px;">Top navigation bar background</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col s6">
                                    <label style="display: block; margin-bottom: 10px; font-weight: bold;">Footer Color</label>
                                    <input type="color" name="footer_color" value="{{ isset($settings) && $settings->footer_color ? $settings->footer_color : '#00695c' }}" style="width: 100%; height: 50px; border: 1px solid #ddd; cursor: pointer;">
                                    <p style="font-size: 12px; color: #999; margin-top: 5px;">Main footer background</p>
                                </div>
                                <div class="col s6">
                                    <label style="display: block; margin-bottom: 10px; font-weight: bold;">Second Footer Color</label>
                                    <input type="color" name="footer_bottom_color" value="{{ isset($settings) && $settings->footer_bottom_color ? $settings->footer_bottom_color : '#000000' }}" style="width: 100%; height: 50px; border: 1px solid #ddd; cursor: pointer;">
                                    <p style="font-size: 12px; color: #999; margin-top: 5px;">Bottom copyright bar background</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col s6">
                                    <label style="display: block; margin-bottom: 10px; font-weight: bold;">Text Color</label>
                                    <input type="color" name="text_color" value="{{ isset($settings) && $settings->text_color ? $settings->text_color : '#333333' }}" style="width: 100%; height: 50px; border: 1px solid #ddd; cursor: pointer;">
                                    <p style="font-size: 12px; color: #999; margin-top: 5px;">Primary text color</p>
                                </div>
                                <div class="col s6">
                                    <label style="display: block; margin-bottom: 10px; font-weight: bold;">Light Text Color</label>
                                    <input type="color" name="text_light_color" value="{{ isset($settings) && $settings->text_light_color ? $settings->text_light_color : '#777777' }}" style="width: 100%; height: 50px; border: 1px solid #ddd; cursor: pointer;">
                                    <p style="font-size: 12px; color: #999; margin-top: 5px;">Secondary/muted text color</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col s6">
                                    <label style="display: block; margin-bottom: 10px; font-weight: bold;">🎨 Materialize Primary Color</label>
                                    <input type="color" name="materialize_primary_color" value="{{ isset($settings) && $settings->materialize_primary_color ? $settings->materialize_primary_color : '#26a69a' }}" style="width: 100%; height: 50px; border: 1px solid #ddd; cursor: pointer;">
                                    <p style="font-size: 12px; color: #999; margin-top: 5px;">Default color for all buttons, links, progress bars, and interactive elements in Materialize UI</p>
                                </div>
                            </div>

                            <h6 style="color: #1976D2; margin-top: 20px; margin-bottom: 15px;">🔘 Button Colors</h6>

                            <div class="row">
                                <div class="col s6">
                                    <label style="display: block; margin-bottom: 10px; font-weight: bold;">Primary Button Color</label>
                                    <input type="color" name="button_primary_color" value="{{ isset($settings) && $settings->button_primary_color ? $settings->button_primary_color : '#26a69a' }}" style="width: 100%; height: 50px; border: 1px solid #ddd; cursor: pointer;">
                                    <p style="font-size: 12px; color: #999; margin-top: 5px;">Main button background color</p>
                                </div>
                                <div class="col s6">
                                    <label style="display: block; margin-bottom: 10px; font-weight: bold;">Primary Button Text Color</label>
                                    <input type="color" name="button_primary_text_color" value="{{ isset($settings) && $settings->button_primary_text_color ? $settings->button_primary_text_color : '#ffffff' }}" style="width: 100%; height: 50px; border: 1px solid #ddd; cursor: pointer;">
                                    <p style="font-size: 12px; color: #999; margin-top: 5px;">Main button text color</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col s6">
                                    <label style="display: block; margin-bottom: 10px; font-weight: bold;">Secondary Button Color</label>
                                    <input type="color" name="button_secondary_color" value="{{ isset($settings) && $settings->button_secondary_color ? $settings->button_secondary_color : '#7e57c2' }}" style="width: 100%; height: 50px; border: 1px solid #ddd; cursor: pointer;">
                                    <p style="font-size: 12px; color: #999; margin-top: 5px;">Alternative button background color</p>
                                </div>
                                <div class="col s6">
                                    <label style="display: block; margin-bottom: 10px; font-weight: bold;">Secondary Button Text Color</label>
                                    <input type="color" name="button_secondary_text_color" value="{{ isset($settings) && $settings->button_secondary_text_color ? $settings->button_secondary_text_color : '#ffffff' }}" style="width: 100%; height: 50px; border: 1px solid #ddd; cursor: pointer;">
                                    <p style="font-size: 12px; color: #999; margin-top: 5px;">Alternative button text color</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col s6">
                                    <label style="display: block; margin-bottom: 10px; font-weight: bold;">Danger Button Color</label>
                                    <input type="color" name="button_danger_color" value="{{ isset($settings) && $settings->button_danger_color ? $settings->button_danger_color : '#f44336' }}" style="width: 100%; height: 50px; border: 1px solid #ddd; cursor: pointer;">
                                    <p style="font-size: 12px; color: #999; margin-top: 5px;">Delete/danger button background color</p>
                                </div>
                                <div class="col s6">
                                    <label style="display: block; margin-bottom: 10px; font-weight: bold;">Danger Button Text Color</label>
                                    <input type="color" name="button_danger_text_color" value="{{ isset($settings) && $settings->button_danger_text_color ? $settings->button_danger_text_color : '#ffffff' }}" style="width: 100%; height: 50px; border: 1px solid #ddd; cursor: pointer;">
                                    <p style="font-size: 12px; color: #999; margin-top: 5px;">Delete/danger button text color</p>
                                </div>
                            </div>
                        </div>

                        <!-- TYPOGRAPHY SECTION -->
                        <div style="background-color: #f9f9f9; padding: 15px; border-radius: 4px; margin-top: 20px;">
                            <h5 style="color: #1976D2; margin-top: 0;">📝 Typography</h5>
                            
                            <div class="input-field">
                                <select name="font_family" id="font_family">
                                    <option value="Roboto" {{ isset($settings) && $settings->font_family == 'Roboto' ? 'selected' : '' }}>Roboto</option>
                                    <option value="Arial" {{ isset($settings) && $settings->font_family == 'Arial' ? 'selected' : '' }}>Arial</option>
                                    <option value="Helvetica" {{ isset($settings) && $settings->font_family == 'Helvetica' ? 'selected' : '' }}>Helvetica</option>
                                    <option value="Georgia" {{ isset($settings) && $settings->font_family == 'Georgia' ? 'selected' : '' }}>Georgia</option>
                                    <option value="Verdana" {{ isset($settings) && $settings->font_family == 'Verdana' ? 'selected' : '' }}>Verdana</option>
                                    <option value="Times New Roman" {{ isset($settings) && $settings->font_family == 'Times New Roman' ? 'selected' : '' }}>Times New Roman</option>
                                </select>
                                <label for="font_family">Font Family</label>
                            </div>

                            <div class="row">
                                <div class="input-field col s4">
                                    <input id="heading_size" type="number" class="validate" name="heading_size" min="18" max="48" value="{{ isset($settings) && $settings->heading_size ? $settings->heading_size : 32 }}">
                                    <label for="heading_size">Heading Size (px)</label>
                                </div>
                                <div class="input-field col s4">
                                    <input id="body_size" type="number" class="validate" name="body_size" min="10" max="20" value="{{ isset($settings) && $settings->body_size ? $settings->body_size : 14 }}">
                                    <label for="body_size">Body Text Size (px)</label>
                                </div>
                                <div class="input-field col s4">
                                    <input id="small_size" type="number" class="validate" name="small_size" min="8" max="14" value="{{ isset($settings) && $settings->small_size ? $settings->small_size : 12 }}">
                                    <label for="small_size">Small Text Size (px)</label>
                                </div>
                            </div>
                        </div>

                        <!-- DASHBOARD COMPONENTS SECTION -->
                        <div style="background-color: #f9f9f9; padding: 15px; border-radius: 4px; margin-top: 20px;">
                            <h5 style="color: #1976D2; margin-top: 0;">📊 Dashboard Components</h5>
                            
                            <div class="switch">
                                <label style="font-size: 14px;">
                                    <input type="checkbox" name="show_dashboard_stats" value="1" {{ isset($settings) && $settings->show_dashboard_stats ? 'checked' : 'checked' }} />
                                    <span class="lever"></span>
                                    Show Statistics Cards
                                </label>
                            </div>

                            <div class="switch">
                                <label style="font-size: 14px;">
                                    <input type="checkbox" name="show_dashboard_charts" value="1" {{ isset($settings) && $settings->show_dashboard_charts ? 'checked' : 'checked' }} />
                                    <span class="lever"></span>
                                    Show Dashboard Charts
                                </label>
                            </div>

                            <div class="switch">
                                <label style="font-size: 14px;">
                                    <input type="checkbox" name="show_recent_activities" value="1" {{ isset($settings) && $settings->show_recent_activities ? 'checked' : 'checked' }} />
                                    <span class="lever"></span>
                                    Show Recent Activities
                                </label>
                            </div>

                            <div class="switch">
                                <label style="font-size: 14px;">
                                    <input type="checkbox" name="show_quick_actions" value="1" {{ isset($settings) && $settings->show_quick_actions ? 'checked' : 'checked' }} />
                                    <span class="lever"></span>
                                    Show Quick Action Buttons
                                </label>
                            </div>

                            <div class="switch">
                                <label style="font-size: 14px;">
                                    <input type="checkbox" name="show_notifications" value="1" {{ isset($settings) && $settings->show_notifications ? 'checked' : 'checked' }} />
                                    <span class="lever"></span>
                                    Show Notifications Panel
                                </label>
                            </div>
                        </div>

                        <!-- LAYOUT CONFIGURATION SECTION -->
                        <div style="background-color: #f9f9f9; padding: 15px; border-radius: 4px; margin-top: 20px;">
                            <h5 style="color: #1976D2; margin-top: 0;">⚙️ Layout Configuration</h5>
                            
                            <div class="input-field">
                                <select name="dashboard_layout" id="dashboard_layout">
                                    <option value="grid" {{ isset($settings) && $settings->dashboard_layout == 'grid' ? 'selected' : '' }}>Grid Layout</option>
                                    <option value="list" {{ isset($settings) && $settings->dashboard_layout == 'list' ? 'selected' : '' }}>List Layout</option>
                                </select>
                                <label for="dashboard_layout">Dashboard Layout</label>
                            </div>

                            <div class="input-field">
                                <input id="sidebar_width" type="number" class="validate" name="sidebar_width" min="150" max="400" value="{{ isset($settings) && $settings->sidebar_width ? $settings->sidebar_width : 250 }}">
                                <label for="sidebar_width">Sidebar Width (pixels)</label>
                            </div>

                            <div class="switch">
                                <label style="font-size: 14px;">
                                    <input type="checkbox" name="sticky_navbar" value="1" {{ isset($settings) && $settings->sticky_navbar ? 'checked' : 'checked' }} />
                                    <span class="lever"></span>
                                    Sticky Navigation Bar (Fixed at top)
                                </label>
                            </div>

                            <div class="switch">
                                <label style="font-size: 14px;">
                                    <input type="checkbox" name="show_breadcrumbs" value="1" {{ isset($settings) && $settings->show_breadcrumbs ? 'checked' : 'checked' }} />
                                    <span class="lever"></span>
                                    Show Breadcrumb Navigation
                                </label>
                            </div>
                        </div>

                        <!-- EFFECTS & ANIMATIONS SECTION -->
                        <div style="background-color: #f9f9f9; padding: 15px; border-radius: 4px; margin-top: 20px;">
                            <h5 style="color: #1976D2; margin-top: 0;">✨ Effects & Animations</h5>
                            
                            <div class="switch">
                                <label style="font-size: 14px;">
                                    <input type="checkbox" name="enable_animations" value="1" {{ isset($settings) && $settings->enable_animations ? 'checked' : 'checked' }} />
                                    <span class="lever"></span>
                                    Enable UI Animations
                                </label>
                            </div>

                            <div class="input-field">
                                <select name="animation_speed" id="animation_speed">
                                    <option value="slow" {{ isset($settings) && $settings->animation_speed == 'slow' ? 'selected' : '' }}>Slow (500ms)</option>
                                    <option value="normal" {{ isset($settings) && $settings->animation_speed == 'normal' ? 'selected' : '' }}>Normal (300ms)</option>
                                    <option value="fast" {{ isset($settings) && $settings->animation_speed == 'fast' ? 'selected' : '' }}>Fast (150ms)</option>
                                </select>
                                <label for="animation_speed">Animation Speed</label>
                            </div>

                            <div class="switch">
                                <label style="font-size: 14px;">
                                    <input type="checkbox" name="enable_shadows" value="1" {{ isset($settings) && $settings->enable_shadows ? 'checked' : 'checked' }} />
                                    <span class="lever"></span>
                                    Enable Box Shadows
                                </label>
                            </div>
                        </div>

                        <!-- ADVANCED UI OPTIONS SECTION -->
                        <div style="background-color: #f9f9f9; padding: 15px; border-radius: 4px; margin-top: 20px;">
                            <h5 style="color: #1976D2; margin-top: 0;">🔧 Advanced UI Options</h5>
                            
                            <div class="input-field">
                                <select name="card_style" id="card_style">
                                    <option value="material" {{ isset($settings) && $settings->card_style == 'material' ? 'selected' : '' }}>Material Design</option>
                                    <option value="flat" {{ isset($settings) && $settings->card_style == 'flat' ? 'selected' : '' }}>Flat Design</option>
                                    <option value="minimal" {{ isset($settings) && $settings->card_style == 'minimal' ? 'selected' : '' }}>Minimal Design</option>
                                </select>
                                <label for="card_style">Card Style</label>
                            </div>

                            <div class="input-field">
                                <input id="border_radius" type="number" class="validate" name="border_radius" min="0" max="20" value="{{ isset($settings) && $settings->border_radius ? $settings->border_radius : 4 }}">
                                <label for="border_radius">Border Radius (pixels)</label>
                            </div>

                            <div class="input-field">
                                <select name="table_style" id="table_style">
                                    <option value="striped" {{ isset($settings) && $settings->table_style == 'striped' ? 'selected' : '' }}>Striped Rows</option>
                                    <option value="bordered" {{ isset($settings) && $settings->table_style == 'bordered' ? 'selected' : '' }}>Bordered</option>
                                    <option value="minimal" {{ isset($settings) && $settings->table_style == 'minimal' ? 'selected' : '' }}>Minimal</option>
                                </select>
                                <label for="table_style">Table Style</label>
                            </div>

                            <div class="switch">
                                <label style="font-size: 14px;">
                                    <input type="checkbox" name="show_table_hover" value="1" {{ isset($settings) && $settings->show_table_hover ? 'checked' : 'checked' }} />
                                    <span class="lever"></span>
                                    Highlight Rows on Hover
                                </label>
                            </div>
                        </div>

                        <div class="input-field text-right right" style="margin-bottom:20px;">
                            
                                <button type="submit" class="btn">
                                    Save Settings
                                </button>                               
                        
                        </div>
                    </form>
                
            
        </div>
    </div>
</div>
@endsection
