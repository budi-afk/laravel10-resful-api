<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressCreateRequest;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    private function getContact(User $user, $idContact)
    {
        $contact = Contact::where("user_id", $user->id)->where("id", $idContact)->first();

        if (!$contact) {
            throw new HttpResponseException(response()->json([
                "errors" => [
                    "message" => [
                        "contact not found"
                    ]
                ]
            ])->setStatusCode(404));
        }

        return $contact;
    }

    public function create($idContact, AddressCreateRequest $request) {
        $user = Auth::user();

        $contact = $this->getContact($user, $idContact);

        $data = $request->validated();

        $address = new Address($data);

        $address->contact_id = $contact->id;

        $address->save();

        return (new AddressResource($address))->response()->setStatusCode(201);
    }

    
}
