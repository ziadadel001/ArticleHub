<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    // Fetch and return a list of articles
    public function index(Request $request)
    {
        // Create a query to get articles ordered by publish date
        $query = Article::query()->latest('publish_date');
        $keyword = $request->input('title');

        // If a keyword is provided, filter articles by title
        if ($keyword) {
            $query->where('title', 'like', "%{$keyword}%");
        }

        // Paginate the results with 2 articles per page
        $articles = $query->paginate(2);

        // If no articles are found, return a 404 response
        if ($articles->isEmpty()) {
            return response()->json(
                [
                    'status' => Response::HTTP_NOT_FOUND,
                    'message' => 'Articles empty',
                    'data' => $articles
                ],
                Response::HTTP_NOT_FOUND
            );
        } else {
            // Otherwise, return the articles with a success message
            return response()->json(
                [
                    'message' => 'List of Articles',
                    'data' => $articles,
                    'status' => Response::HTTP_OK
                ],
                Response::HTTP_OK
            );
        }
    }

    // Store a new article
    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
            'publish_date' => 'required',
        ]);

        // If validation fails, return an error response
        if ($validator->fails()) {
            return response()->json(
                [
                    'error' => $validator->errors(),
                    'message' => 'Validation Failed',
                    'status' => Response::HTTP_UNPROCESSABLE_ENTITY
                ]
            );
        }

        // Try to create the article
        try {
            Article::create([
                'title' => $request->title,
                'content' => $request->content,
                'publish_date' => Carbon::create($request->publish_date)->toDateString(),
            ]);
            return response()->json(
                [
                    'message' => 'Article Created Successfully',
                    'status' => Response::HTTP_OK
                ],
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            // Log any errors and return a server error response
            Log::error('Error storing data: ' . $e->getMessage());
            return response()->json(
                [
                    'error' => 'Server Error',
                    'message' => 'Failed to create article',
                    'status' => Response::HTTP_INTERNAL_SERVER_ERROR
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    // Show a specific article by ID
    public function show($id)
    {
        try {
            // Find the article by ID
            $article = Article::where('id', $id)->first();

            // If the article is found, return it
            if ($article) {
                return response()->json(
                    [
                        'message' => 'Article Found',
                        'data' => $article,
                        'status' => Response::HTTP_OK
                    ],
                    Response::HTTP_OK
                );
            } else {
                // If the article is not found, return a 404 response
                return response()->json(
                    [
                        'message' => 'Article not found',
                        'status' => Response::HTTP_NOT_FOUND
                    ],
                    Response::HTTP_NOT_FOUND
                );
            }
        } catch (Exception $e) {
            // Log any errors and return a server error response
            Log::error('Error fetching data: ' . $e->getMessage());
            return response()->json(
                [
                    'error' => 'Server Error',
                    'message' => 'Failed to fetch article',
                    'status' => Response::HTTP_INTERNAL_SERVER_ERROR
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    // Update an existing article
    public function update(Request $request, $id)
    {
        // Find the article by ID
        $article = Article::find($id);

        // If the article is not found, return a 404 response
        if (!$article) {
            return response()->json(
                [
                    'message' => 'Article not found',
                    'status' => Response::HTTP_NOT_FOUND
                ],
                Response::HTTP_NOT_FOUND
            );
        } else {
            // Validate the request data
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'content' => 'required',
                'publish_date' => 'required',
            ]);

            // If validation fails, return an error response
            if ($validator->fails()) {
                return response()->json(
                    [
                        'error' => $validator->errors(),
                        'message' => 'Validation Failed',
                        'status' => Response::HTTP_UNPROCESSABLE_ENTITY
                    ]
                );
            }

            // Try to update the article
            try {
                $article->update([
                    'title' => $request->title,
                    'content' => $request->content,
                    'publish_date' => Carbon::create($request->publish_date)->toDateString(),
                ]);

                return response()->json(
                    [
                        'message' => 'Article Updated Successfully',
                        'status' => Response::HTTP_OK
                    ],
                    Response::HTTP_OK
                );
            } catch (Exception $e) {
                // Log any errors and return a server error response
                Log::error('Error updating data: ' . $e->getMessage());
                return response()->json(
                    [
                        'error' => 'Server Error',
                        'message' => 'Failed to update article',
                        'status' => Response::HTTP_INTERNAL_SERVER_ERROR
                    ],
                    Response::HTTP_INTERNAL_SERVER_ERROR
                );
            }
        }
    }

    // Delete an article by ID
    public function destroy($id)
    {
        // Find the article by ID
        $article = Article::find($id);

        // If the article is not found, return a 404 response
        if (!$article) {
            return response()->json(
                [
                    'message' => 'Article not found',
                    'status' => Response::HTTP_NOT_FOUND
                ],
                Response::HTTP_NOT_FOUND
            );
        } else {
            // Try to delete the article
            try {
                $article->delete();
                return response()->json(
                    [
                        'message' => 'Article Deleted Successfully',
                        'status' => Response::HTTP_OK
                    ],
                    Response::HTTP_OK
                );
            } catch (Exception $e) {
                // Log any errors and return a server error response
                Log::error('Error deleting data: ' . $e->getMessage());
                return response()->json(
                    [
                        'error' => 'Server Error',
                        'message' => 'Failed to delete article',
                        'status' => Response::HTTP_INTERNAL_SERVER_ERROR
                    ],
                    Response::HTTP_INTERNAL_SERVER_ERROR
                );
            }
        }
    }
}
