<?php

namespace App\Http\Controllers;

use App\Mapping;
use Illuminate\Http\Request;

const API_ENDPOINT_ID_PLACEHOLDER = '*****';

class ComparisionController extends Controller
{

    public function compare(Request $request)
    {
        $csvIds = $request->get('ids');
        $subCatId = $request->get('subcat');

        // Base case
        if (!isset($csvIds) || empty($csvIds) || !isset($subCatId) || empty($subCatId)) {
            return [];
        }

        return response()->json([
            "fields" => [
                "Price",
                "Area (in Sqft)",
                "Address",
                "Available From",
                "Furnished",
                "Registration Charges",
                "Carpet Area"
            ],
            "products" => [
                [
                    "id" => 1,
                    "title" => "Sobha City",
                    "image" => "/r1/20170731/ak_1200_1396257863-1501504007_300x300.png",
                    "fields" => [
                        "Price" => 1200000,
                        "Area (in Sqft)" => 1300,
                        "Address" => "Thanisandra, Bangalore",
                        "Available From" => "10 March 2019",
                        "Furnished" => true,
                        "Registration Charges" => 150000,
                        "Carpet Area" => 1200
                    ]
                ],
                [
                    "id" => 2,
                    "title" => "RES Residency",
                    "image" => "r1/20170731/ak_1200_1324575520-1501504002_300x300.jpeg",
                    "fields" => [
                        "Price" => 1200000,
                        "Area (in Sqft)" => 1300,
                        "Address" => "Thanisandra, Bangalore",
                        "Available From" => "10 March 2019",
                        "Furnished" => true,
                        "Registration Charges" => 150000,
                        "Carpet Area" => 1200
                    ]
                ],
                [
                    "id" => 3,
                    "title" => "Manyata Residency",
                    "image" => "r1/20170731/ak_1200_1422121845-1501504007_300x300.jpeg",
                    "fields" => [
                        "Price" => 19000000,
                        "Area (in Sqft)" => 1300,
                        "Address" => "Nagawara, Bangalore",
                        "Available From" => "18 March 2019",
                        "Furnished" => true,
                        "Registration Charges" => 150000,
                        "Carpet Area" => 1500
                    ]
                ]
            ]
        ]);
    }


    public function doCompare(Request $request) {
        $subCatId = $request->get('subcat', null);
        $csvProductIds = $request->get('ids', null);

        // Validate the parameters
        if (empty($subCatId) || empty($csvProductIds)) {
            return [];
        }

        $productIds = explode(',', $csvProductIds);

        // Supported comparision is 2 or 3
        if (count($productIds) != 2 && count($productIds) != 3) {
            return [];
        }

        // Get the mapping from the database
        $mapping = Mapping::where(['sub_cat_id' => $subCatId])
                    ->first();

        // If no mapping found, return
        if (empty($mapping)) {
            return [];
        }

        $mapping = $mapping->toArray();

        // Configuration
        $endpoint = $mapping['api_endpoint'];

        // Return if the API end point doesn't have a id placeholder
        if (!str_contains($endpoint, API_ENDPOINT_ID_PLACEHOLDER)) {
            return [];
        }

        $products = [];

        foreach ($productIds as $productId) {
            $productUrl = str_replace(API_ENDPOINT_ID_PLACEHOLDER, $productId, $endpoint);
            $headers = empty($mapping['api_headers']) ? [] : json_decode($mapping['api_headers'], true);

            if (empty($headers)) {
                $headers = [];
            }

            $curlHeaders = ["Content-Type: application/json"];

            foreach ($headers as $key => $value) {
                $curlHeaders[] = $key . ': ' . $value;
            }

            // Curl
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $productUrl);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $curlHeaders);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($curl);
            $products[] = json_decode($response, true);
        }

        $comparision = [];

        foreach ($products as $product) {
            $comparision[] = Mapping::trasformAttributes($subCatId, $product);
        }

        return $comparision;
    }

    public function test(Request $request)
    {
        // dd($request->sub_cat_id);
        return response()->json(['result' => Mapping::trasformAttributes($request->sub_cat_id, $this->mockData())]);
    }

    private function mockData() {
        return json_decode('{
          "statusCode": 200,
          "message": "success",
          "data": {
            "propertySnippet": {
              "id": 309818290,
              "description": "Project is located next to Acharya Nagarjuna University, a well-known university in Andhra Pradesh. The site is in close proximity to many colleges, schools, healthcare services, and other civic utilities as well as it is well connected by all modes of transportation. Developers have kept in mind all the requirements and future needs of the residents to give all the modern facilities with well-designed layout of villas and garden.\nRamakrishna Villaas Greenz includes Landscaped Garden, Indoor Games, Earthquake Resistant, CCTV Cameras, Community Hall, Swimming Pool, Gymnasium, Play Area, Intercom, Rain Water Harvesting, Waste Disposal, Club House, Car Parking, Fire Safety, Maintenance Staff, Jogging Track, 24Hr Backup Electricity, Drainage and Sewage Treatment, Vaastu / Feng Shui, Street Light and Security. ",
              "categoryName": "Real Estate,Villas/Bungalows for Rent",
              "category": "Residential",
              "title": "2025 Sq. ft Residential Villa for rent in Kaza, Guntur",
              "price": 28000,
              "userType": "Broker",
              "mobileNo": "9989990892",
              "adStyle": [
                "B"
              ],
              "dEmail": null,
              "email": "umasmareddy@gmail.com",
              "referrer": "https://www.quikr.com/homes/postad",
              "area": "2025",
              "images": [
                {
                  "id": 79043314,
                  "awsPath": "i6/20180711/2025-Sq-ft-Residential-Villa-for-rent-in-Kaza--Guntur-VB201705171774173-ak_LWBP612469700-1531287282nr424x318sm124x93sq88x66lg728x546gv262x175.png",
                  "caption": null,
                  "name": null
                },
                {
                  "id": 79043315,
                  "awsPath": "i6/20180711/2025-Sq-ft-Residential-Villa-for-rent-in-Kaza--Guntur-VB201705171774173-ak_LWBP1315882916-1531287265nr424x318sm124x93sq88x66lg728x546gv262x175.png",
                  "caption": null,
                  "name": null
                },
                {
                  "id": 79043316,
                  "awsPath": "i4/20180711/2025-Sq-ft-Residential-Villa-for-rent-in-Kaza--Guntur-VB201705171774173-ak_LWBP1024327848-1531287266nr424x318sm124x93sq88x66lg728x546gv262x175.png",
                  "caption": null,
                  "name": null
                },
                {
                  "id": 79043317,
                  "awsPath": "i5/20180711/2025-Sq-ft-Residential-Villa-for-rent-in-Kaza--Guntur-VB201705171774173-ak_LWBP558957583-1531287258nr424x318sm124x93sq88x66lg728x546gv262x175.png",
                  "caption": null,
                  "name": null
                },
                {
                  "id": 79043318,
                  "awsPath": "i4/20180711/2025-Sq-ft-Residential-Villa-for-rent-in-Kaza--Guntur-VB201705171774173-ak_LWBP1869570792-1531287274nr424x318sm124x93sq88x66lg728x546gv262x175.png",
                  "caption": null,
                  "name": null
                },
                {
                  "id": 79043319,
                  "awsPath": "i6/20180711/2025-Sq-ft-Residential-Villa-for-rent-in-Kaza--Guntur-VB201705171774173-ak_LWBP132914984-1531287273nr424x318sm124x93sq88x66lg728x546gv262x175.png",
                  "caption": null,
                  "name": null
                }
              ],
              "createdTime": 1531286946,
              "modifiedTime": 1531287456,
              "latitude": "16.382409",
              "longitude": "80.537539",
              "city": "Guntur",
              "cityId": "1003961",
              "availableFrom": "2018-07-11",
              "buildingName": null,
              "unitNo": null,
              "floorNo": "0",
              "locality": [
                "Kaza"
              ],
              "localityIds": [
                10638
              ],
              "mobilePrivacy": 0,
              "furnished": "Unfurnished",
              "attributes": {
                "amenities": [
                  {
                    "name": "Club House"
                  },
                  {
                    "name": "Gated Community"
                  },
                  {
                    "name": "Car Parking"
                  },
                  {
                    "name": "Play Area"
                  },
                  {
                    "name": "Maintenance Staff"
                  },
                  {
                    "name": "CCTV Cameras"
                  },
                  {
                    "name": "Earthquake Resistant"
                  },
                  {
                    "name": "Indoor Games"
                  }
                ]
              },
              "type": "Villa",
              "propertyFor": "rent",
              "details": {
                "floor": 0,
                "totalFloor": 0,
                "projectName": "Ramakrishna Venuzia Villas",
                "views": [
                  "Garden",
                  "Road"
                ],
                "builderName": null
              },
              "configuration": [
                {
                  "key": "balcony",
                  "count": "1"
                },
                {
                  "key": "bathroom",
                  "count": "3"
                },
                {
                  "key": "bedroom",
                  "count": "3"
                }
              ],
              "furnishItems": [],
              "views": [
                "Garden",
                "Road"
              ],
              "isGrabHouse": 0,
              "isQAP": 0,
              "cfListingId": null,
              "listingViews": null,
              "cfCityId": null,
              "streetAddress": "",
              "registrationCharges": null,
              "adFeatures": {
                "carpetArea": 1950,
                "unitFacing": "Garden,Road",
                "tower": null,
                "openArea": null,
                "flooringType": null,
                "ownership": null,
                "brokerageTerms": null,
                "propertyAge": null,
                "serventAccomodation": null,
                "depositPrice": null,
                "rentMaintenance": null,
                "maintenanceCharges": null,
                "registrationCharges": null,
                "bachelorAllowed": null,
                "petAllowed": null,
                "tenantVeg": null,
                "waterService": null,
                "electricityBackup": null,
                "parking": null,
                "twoWheelerParking": null,
                "fourWheelerParking": null,
                "isVisitorParking": null,
                "plotSite": null,
                "terrace": null,
                "gardenArea": null,
                "isGardenArea": 0,
                "projectId": null,
                "projectName": null,
                "cfBuilderId": "tjd24s",
                "cfBuilderName": "Ramakrishna Housing Pvt Ltd ",
                "pauseMask": null,
                "degree360ViewCount": 0,
                "degree360ViewDetails": null,
                "cfRegionName": null,
                "cfRegionId": null,
                "cfAreaName": null,
                "cfAreaId": null,
                "isVerified": null,
                "builtUpArea": 2025,
                "adStatus": "INACTIVE",
                "cfCityId": null,
                "lastModifiedOn": null,
                "saleType": null,
                "includeRegistrationCharges": null,
                "projectUrl": null,
                "house_id": null,
                "block": null,
                "isNegotiable": null,
                "isBrokerageNegotiable": null,
                "floorplans": null,
                "plotArea": null,
                "brokerCompany": null,
                "virtualTourAvailable": 0,
                "locationUrl": "/location-listing/null",
                "listingUrl": "/listing/2025-sq-ft-residential-villa-for-rent-in-kaza-guntur/null",
                "listingApprovals": {
                  "land_approvals": null,
                  "loan_approvals": null
                },
                "projectMasthead": null,
                "listingMasthead": null,
                "videoUrl": null,
                "hasVideo": null,
                "builderUrl": "/ramakrishna-housing-pvt-ltd-/builder/tjd24s",
                "zoneId": null,
                "accommodationFor": null,
                "directionFacing": null,
                "cf_ad_style": null,
                "projectImages": null,
                "occupancyType": null,
                "pgType": null
              },
              "gender": null,
              "carpetArea": "1950",
              "companyName": null,
              "reraNo": null,
              "reraUrl": null,
              "transaction": null,
              "demail": null
            },
            "projectSnippet": {
              "id": 220697,
              "name": "Ramakrishna Venuzia Villas",
              "latitude": "16.382409",
              "longitude": "80.537539",
              "status": "upcoming",
              "imageUrl": null,
              "logoImageUrl": null,
              "address": {
                "street": null,
                "locality": "Kaza",
                "city": "Guntur",
                "cityId": "1003961",
                "localityId": "10638"
              },
              "qhAddress": "Near Acharya Nagarjuna University, Kaza, Guntur, Andhra Pradesh, INDIA.",
              "mapUrl": "http://maps.google.com/maps?q=16.382409,80.537539",
              "area": null,
              "priceRange": {
                "low": 0,
                "high": 0
              },
              "builder": {
                "name": "Ramakrishna Housing Pvt Ltd ",
                "profileUrl": null
              },
              "type": "Villa",
              "category": "Residential",
              "description": "<p>Presenting, Ramakrishna Venuzia Villas - an address that is an oasis of calm, peace and magnificence in the hustle-bustle of the city, Guntur. Your home will now serve as a perfect getaway after a tiring day at work, as Ramakrishna Venuzia Villas ambiance will make you forget that you are in the heart of the city, Kaza. Ramakrishna Venuzia Villas is a large project spread over an area of 27.00 acres. Villas in Ramakrishna Venuzia Villas comprises of beautiful houses in Guntur. Ramakrishna Venuzia Villas brings a lifestyle that befits Royalty with the batch of magnificent Villas at Kaza. These Residential Villas in Guntur offers limited edition luxury boutique houses that amazingly escapes the noise of the city center. Ramakrishna Venuzia Villas is built by a renowned name in construction business, Ramakrishna Housing Pvt Ltd at Kaza, Guntur. The floor plan of Ramakrishna Venuzia Villas presents the most exciting and dynamic floor plans designed for a lavish lifestyle with 3 floors. The master plan of Ramakrishna Venuzia Villas offers people a strong connection to their surroundings, promoting a sense of community whilst balancing this with a distinct private address for individual homeowners.</p>\n\n<p> </p>\n\n<p>Location Advantage: There are number of benefits of living in Villas with a good locality. The location of Ramakrishna Venuzia Villas makes sure that the home-seekers are choosing the right Villas for themselves. It is one of the most prestigious address of Guntur with many facilities and utilities nearby Kaza.</p>\n\n<p>Address: The complete address of Ramakrishna Venuzia Villas is Near Acharya Nagarjuna University, Kaza, Guntur, Andhra Pradesh, INDIA..</p>\n",
              "totalListings": 2,
              "totalFollowers": null,
              "totalRatings": null,
              "averageRating": null,
              "isOnlineBooking": null,
              "bookingAmount": null,
              "localityId": "10638",
              "launchDate": null,
              "completionDate": null,
              "projectUnits": [
                {
                  "id": 312212,
                  "type": null,
                  "subType": "Villa",
                  "superArea": 0,
                  "carpetArea": 0,
                  "plotArea": 0,
                  "bedRooms": "",
                  "bathRooms": "0",
                  "balconies": "0",
                  "price": null,
                  "areaUnit": "Sq. Ft.",
                  "bspPrice": null,
                  "minRentalPrice": null,
                  "maxRantalPrice": null,
                  "minSalePrice": null,
                  "maxSalePrice": null,
                  "floorPlanImage": [],
                  "cfUnitId": null,
                  "floorPlansCf": null,
                  "virtualTourLink": null,
                  "source": null,
                  "isAvailable": 1,
                  "emodelLink": null
                }
              ],
              "reraRegistrationNo": null,
              "reraLink": null,
              "noOfUnits": 0,
              "noOfFloors": 3,
              "totalProjectArea": "27.00",
              "isQuikrReality": null
            },
            "builderSnippet": {
              "id": 10746,
              "builderImages": [
                {
                  "imageUrl": "/r1/20180315/ak_358_814414202-1521095842_80x80.png",
                  "caption": "Builder_Logo",
                  "udate": null,
                  "source": null,
                  "name": null,
                  "bhk": null,
                  "sequenceNo": null
                }
              ],
              "builder": {
                "name": "Ramakrishna Housing Pvt Ltd ",
                "profileUrl": null
              },
              "logoImage": "/r1/20180315/ak_358_814414202-1521095842_80x80.png",
              "bannerImage": null,
              "address": {
                "street": null,
                "locality": null,
                "city": "Vijayawada",
                "cityId": "520001",
                "localityId": null
              },
              "description": "Ramakrishna Housing has earned a formidable reputation for delivering innovative solutions that go beyond plotting and enter the realm of integrated projects that incorporate high-end residential spaces with walk-to-work environments. Ramakrishna Venuzia is a residential commune that blends the finesse of global living with the essence of nature to swathe you in a golden glow of luxury, purity and abundance. Ramakrishna Housing is the member in IGBC – Indian Green Building Council.",
              "types": [],
              "cities": [
                {
                  "id": "520001",
                  "name": "Vijayawada"
                },
                {
                  "id": "531001",
                  "name": "Vizag"
                },
                {
                  "id": "1003961",
                  "name": "Guntur"
                }
              ],
              "totalprojects": 2,
              "totalCompletedProjects": 1,
              "totalOngoingProjects": 1,
              "totalUpcomingProjects": null,
              "totalListings": null,
              "totalViews": null,
              "totalFollowers": null,
              "totalRatings": null,
              "averageRating": null
            },
            "sellerSnippet": {
              "id": "151071209",
              "name": "uma sankar reddy",
              "email": "umasmareddy@gmail.com",
              "mobileNo": "9989990892",
              "profileUrl": null,
              "profileImageUrl": null,
              "otherAdsUrl": null,
              "totalRatings": null,
              "averageRating": null,
              "rating": null,
              "postedBy": null
            },
            "amenitySnippet": {
              "amenities": []
            },
            "ghSnippet": null
          }
        }', true);
    }

}