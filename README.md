# User Profiles

## Description

User Profiles allows administrators to create highly configurable user profile forms for site users. 


## Installation

User Profiles requires the Record Relations plugin.

Copy the files to the `plugins` directory, and install from the Omeka plugins page

## Configuration

User Profiles can be configured to automatically add a link from an item page to the profile of the user who created the item.

If the Guest User plugin is installed, User Profiles will integrate into the user bar at the top of public pages. Text about user profiles should be added to the Guest User configurations

## Usage

User Profiles allows administrators to define different profile types. For example, you could create a "Basics" profile type for name and location, and a separate "Education" profile type for educational background. You can (and should) also give a description for the profile type. Within each type, you define the fields you want to use by giving them a name, a description, and a field type (short text, long text, single checkbox, multiple checkbox, radio buttons, or dropdown). For the non-text, also provide the valid values.

Types can be made private, meaning only site administrators can see the information. This is useful for sites collecting items and information from users in order to do research. They can also be made required, which prompts users to complete their profiles is they have not done so.

