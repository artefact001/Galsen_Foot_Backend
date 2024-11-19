<?php

namespace App\Services;

use App\Models\Galerie;
use Illuminate\Database\Eloquent;
use Illuminate\Support\Facades\Storage;

class GalerieService
{
    /**
     * Retrieve all galerie records.
     *
     * @return Collection
     */
    public function getAll()
    {
        // return Galerie::all();
    }

    /**
     * Find a galerie by ID.
     *
     * @param int $id
     * @return Galerie|null
     */
    public function findById(int $id): ?Galerie
    {
        return Galerie::find($id);
    }

    /**
     * Create a new galerie record with an image upload.
     *
     * @param array $data
     * @return Galerie
     */
    public function create(array $data): Galerie
{
    // Ensure the 'image' file is in the correct format before saving
    if (isset($data['image'])) {
        $imagePath = $data['image']->store('images');
        $data['image'] = $imagePath;
    }

    // Create and return the Galerie instance
    return Galerie::create($data);  // This should return a Galerie instance
}

    /**
     * Update a galerie record by ID with a new image upload if provided.
     *
     * @param int $id
     * @param array $data
     * @return Galerie|null
     */
    public function update(int $id, array $data): ?Galerie
    {
        $galerie = Galerie::find($id);
        if ($galerie) {
            if (isset($data['image'])) {
                // Delete the old image if it exists
                Storage::disk('public')->delete($galerie->image);

                // Store the new image
                $data['image'] = $data['image']->store('galerie_images', 'public');
            }
            $galerie->update($data);
        }
        return $galerie;
    }

    /**
     * Delete a galerie record by ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $galerie = Galerie::find($id);
        if ($galerie) {
            // Delete the image file from storage
            Storage::disk('public')->delete($galerie->image);
            return $galerie->delete();
        }
        return false;
    }
}
