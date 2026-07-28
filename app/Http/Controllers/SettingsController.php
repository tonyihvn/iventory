<?php

namespace App\Http\Controllers;

use App\settings;
use App\audit;
use Auth;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $settings = settings::first();
        return view('settings')->with(['settings'=>$settings]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'organization_name' => 'required|min:3'
        ]);
        
        // Ensure upload directories exist
        if (!file_exists('uploads')) {
            mkdir('uploads', 0755, true);
        }
        if (!file_exists('uploads/backgrounds')) {
            mkdir('uploads/backgrounds', 0755, true);
        }
        
        if ($request->hasFile('logo')) {
            $logoname = $request->file('logo')->getClientOriginalName();
            $file = request()->file('logo');
            $file->move('uploads', $logoname);
        } else {
            $logoname = "";
        }

        settings::create([
            'organization_name'=>$request->organization_name,
            'description'=>$request->description,
            'logo'=>$logoname,
            'address'=>$request->address,
            'phone_number'=>$request->phone_number,                
            'copyright'=>$request->copyright,
            'disable_pagination'=>$request->has('disable_pagination') ? 1 : 0,
            // Site Builder - Theme
            'theme_mode'=>$request->theme_mode ?? 'light',
            // Site Builder - Colors
            'primary_color'=>$request->primary_color ?? '#2196F3',
            'secondary_color'=>$request->secondary_color ?? '#FFC107',
            'background_color'=>$request->background_color ?? '#FFFFFF',
            'sidebar_color'=>$request->sidebar_color ?? '#263238',
            'topbar_color'=>$request->topbar_color ?? '#009688',
            'footer_color'=>$request->footer_color ?? '#00695c',
            'footer_bottom_color'=>$request->footer_bottom_color ?? '#000000',
            'text_color'=>$request->text_color ?? '#333333',
            'text_light_color'=>$request->text_light_color ?? '#777777',
            'materialize_primary_color'=>$request->materialize_primary_color ?? '#26a69a',
            // Site Builder - Button Colors
            'button_primary_color'=>$request->button_primary_color ?? '#26a69a',
            'button_primary_text_color'=>$request->button_primary_text_color ?? '#ffffff',
            'button_secondary_color'=>$request->button_secondary_color ?? '#7e57c2',
            'button_secondary_text_color'=>$request->button_secondary_text_color ?? '#ffffff',
            'button_danger_color'=>$request->button_danger_color ?? '#f44336',
            'button_danger_text_color'=>$request->button_danger_text_color ?? '#ffffff',
            // Site Builder - Typography
            'font_family'=>$request->font_family ?? 'Roboto',
            'heading_size'=>$request->heading_size ?? 32,
            'body_size'=>$request->body_size ?? 14,
            'small_size'=>$request->small_size ?? 12,
            // Site Builder - Dashboard Components
            'show_dashboard_stats'=>$request->has('show_dashboard_stats') ? 1 : 0,
            'show_dashboard_charts'=>$request->has('show_dashboard_charts') ? 1 : 0,
            'show_recent_activities'=>$request->has('show_recent_activities') ? 1 : 0,
            'show_quick_actions'=>$request->has('show_quick_actions') ? 1 : 0,
            'show_notifications'=>$request->has('show_notifications') ? 1 : 0,
            // Site Builder - Layout
            'dashboard_layout'=>$request->dashboard_layout ?? 'grid',
            'sidebar_width'=>$request->sidebar_width ?? 250,
            'sticky_navbar'=>$request->has('sticky_navbar') ? 1 : 0,
            'show_breadcrumbs'=>$request->has('show_breadcrumbs') ? 1 : 0,
            // Site Builder - Effects
            'enable_animations'=>$request->has('enable_animations') ? 1 : 0,
            'animation_speed'=>$request->animation_speed ?? 'normal',
            'enable_shadows'=>$request->has('enable_shadows') ? 1 : 0,
            // Site Builder - Advanced UI
            'card_style'=>$request->card_style ?? 'material',
            'border_radius'=>$request->border_radius ?? 4,
            'table_style'=>$request->table_style ?? 'striped',
            'show_table_hover'=>$request->has('show_table_hover') ? 1 : 0
            ]);
        
        audit::create([
            'action'=>"System Settings By ", 
            'description'=>'System settings',
            'doneby'=>"Admin"
        ]);
        session()->flash('message','The New Settings has been saved successfully!');
        
        return redirect()->back();
            
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\settings  $settings
     * @return \Illuminate\Http\Response
     */
    public function show(settings $settings)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\settings  $settings
     * @return \Illuminate\Http\Response
     */
    public function edit(settings $settings,$id)
    {
        $settings = settings::where('id',$id)->first();
        return view('edit_settings')->with(['settings'=>$settings]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\settings  $settings
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, settings $settings)
    {
        $this->validate($request, [
            'organization_name' => 'required|min:3'
        ]);
        
        // Ensure upload directories exist
        if (!file_exists('uploads')) {
            mkdir('uploads', 0755, true);
        }
        if (!file_exists('uploads/backgrounds')) {
            mkdir('uploads/backgrounds', 0755, true);
        }
        
        if ($request->hasFile('logo')) {
            $logoname = $request->file('logo')->getClientOriginalName();
            $file = request()->file('logo');
            $file->move('uploads', $logoname);
        } else {
            $logoname = $request->oldlogo;
        }

        // Handle login background image
        if ($request->hasFile('login_bg_image')) {
            $login_bg_name = time() . '_login_bg.' . $request->file('login_bg_image')->getClientOriginalExtension();
            $request->file('login_bg_image')->move('uploads/backgrounds', $login_bg_name);
        } else {
            $login_bg_name = $request->old_login_bg ?? null;
        }

        // Handle app background image
        if ($request->hasFile('app_bg_image')) {
            $app_bg_name = time() . '_app_bg.' . $request->file('app_bg_image')->getClientOriginalExtension();
            $request->file('app_bg_image')->move('uploads/backgrounds', $app_bg_name);
        } else {
            $app_bg_name = $request->old_app_bg ?? null;
        }

        $settingsRecord = settings::find($request->id);
        
        if (!$settingsRecord) {
            session()->flash('error', 'Settings record not found!');
            return redirect()->back();
        }
        
        $settingsRecord->update([
            'organization_name'=>$request->organization_name,
            'description'=>$request->description,
            'logo'=>$logoname,
            'login_bg_image'=>$login_bg_name,
            'app_bg_image'=>$app_bg_name,
            'address'=>$request->address,
            'phone_number'=>$request->phone_number,                
            'copyright'=>$request->copyright,
            'disable_pagination'=>$request->has('disable_pagination') ? 1 : 0,
            // Site Builder - Theme
            'theme_mode'=>$request->theme_mode ?? 'light',
            // Site Builder - Colors
            'primary_color'=>$request->primary_color ?? '#2196F3',
            'secondary_color'=>$request->secondary_color ?? '#FFC107',
            'background_color'=>$request->background_color ?? '#FFFFFF',
            'sidebar_color'=>$request->sidebar_color ?? '#263238',
            'topbar_color'=>$request->topbar_color ?? '#009688',
            'footer_color'=>$request->footer_color ?? '#00695c',
            'footer_bottom_color'=>$request->footer_bottom_color ?? '#000000',
            'text_color'=>$request->text_color ?? '#333333',
            'text_light_color'=>$request->text_light_color ?? '#777777',
            'materialize_primary_color'=>$request->materialize_primary_color ?? '#26a69a',
            // Site Builder - Button Colors
            'button_primary_color'=>$request->button_primary_color ?? '#26a69a',
            'button_primary_text_color'=>$request->button_primary_text_color ?? '#ffffff',
            'button_secondary_color'=>$request->button_secondary_color ?? '#7e57c2',
            'button_secondary_text_color'=>$request->button_secondary_text_color ?? '#ffffff',
            'button_danger_color'=>$request->button_danger_color ?? '#f44336',
            'button_danger_text_color'=>$request->button_danger_text_color ?? '#ffffff',
            // Site Builder - Typography
            'font_family'=>$request->font_family ?? 'Roboto',
            'heading_size'=>$request->heading_size ?? 32,
            'body_size'=>$request->body_size ?? 14,
            'small_size'=>$request->small_size ?? 12,
            // Site Builder - Dashboard Components
            'show_dashboard_stats'=>$request->has('show_dashboard_stats') ? 1 : 0,
            'show_dashboard_charts'=>$request->has('show_dashboard_charts') ? 1 : 0,
            'show_recent_activities'=>$request->has('show_recent_activities') ? 1 : 0,
            'show_quick_actions'=>$request->has('show_quick_actions') ? 1 : 0,
            'show_notifications'=>$request->has('show_notifications') ? 1 : 0,
            // Site Builder - Layout
            'dashboard_layout'=>$request->dashboard_layout ?? 'grid',
            'sidebar_width'=>$request->sidebar_width ?? 250,
            'sticky_navbar'=>$request->has('sticky_navbar') ? 1 : 0,
            'show_breadcrumbs'=>$request->has('show_breadcrumbs') ? 1 : 0,
            // Site Builder - Effects
            'enable_animations'=>$request->has('enable_animations') ? 1 : 0,
            'animation_speed'=>$request->animation_speed ?? 'normal',
            'enable_shadows'=>$request->has('enable_shadows') ? 1 : 0,
            // Site Builder - Advanced UI
            'card_style'=>$request->card_style ?? 'material',
            'border_radius'=>$request->border_radius ?? 4,
            'table_style'=>$request->table_style ?? 'striped',
            'show_table_hover'=>$request->has('show_table_hover') ? 1 : 0
            ]);
        
        audit::create([
            'action'=>"System Settings Updated By ",
            'description'=>'System settings was modified',
            'doneby'=>"Admin"
        ]);
        session()->flash('message','The Settings have been updated successfully!');
        
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\settings  $settings
     * @return \Illuminate\Http\Response
     */
    public function destroy(settings $settings)
    {
        //
    }
}
