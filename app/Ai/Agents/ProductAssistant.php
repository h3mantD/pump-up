<?php

declare(strict_types=1);

namespace App\Ai\Agents;

use App\Models\Category;
use App\Models\Product;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;
use Laravel\Ai\Tools\SimilaritySearch;

#[Provider(Lab::Groq)]
final class ProductAssistant implements Agent, Conversational, HasTools
{
    use Promptable;

    /** @var array<Message> */
    private array $conversationHistory = [];

    /**
     * @param  array<array{role: string, content: string}>  $history
     */
    public function withHistory(array $history): self
    {
        $this->conversationHistory = array_map(
            fn (array $msg): Message => new Message($msg['role'], $msg['content']),
            array_slice($history, -20)
        );

        return $this;
    }

    /**
     * @return array<Message>
     */
    public function messages(): iterable
    {
        return $this->conversationHistory;
    }

    public function instructions(): string
    {
        $categories = Category::all()
            ->map(fn (Category $cat): string => "- {$cat->name} (category_id={$cat->id})")
            ->implode("\n");

        return <<<INSTRUCTIONS
        You are a fitness expert and customer support agent for "Pump", a portal that sells gym equipment.
        Help customers find the right equipment, answer questions about products, and provide fitness advice.
        When a customer asks about products, use the similarity search tool to find relevant products from our inventory.

        ## Formatting Rules
        - Present products in a clean, readable markdown format — bold names, prices, stock info
        - Never return raw JSON to the user
        - Always link product names to their detail page using markdown links: [Product Name](/products/{id})
        - When suggesting a category or filtered view, link to the filter URL: [View Cardio Equipment](/products?category_id=1)
        - For price range questions, link to filtered results: [View products under $100](/products?status=available)
        - When listing multiple products, use a table with clickable names

        ## URL Patterns
        - Product detail page: /products/{id}  (use the product's id from search results)
        - Products filtered by category: /products?category_id={id}
        - Products filtered by status: /products?status=available
        - Products filtered by name search: /products?name={search_term}
        - Combine filters: /products?category_id={id}&status=available

        ## Available Categories
        {$categories}
        INSTRUCTIONS;
    }

    /**
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [
            SimilaritySearch::usingModel(
                model: Product::class,
                column: 'embedding',
                minSimilarity: 0.4,
                limit: 10,
            )->withDescription('Search gym equipment products by similarity to a query.'),
        ];
    }
}
