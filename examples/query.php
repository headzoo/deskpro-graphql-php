<?php
use Deskpro\API\GraphQL;

require(__DIR__ . '/../vendor/autoload.php');

$query = '
query {
	chats_get_agent_chats(id: 0) {
		departments
		agent_teams
		agents
		id
		chat_type
		is_pinned
		date_created
		date_last_message
		name
		admin

	}
		agents_get_agents(id: 0) {
		id
		primary_email
		first_name
		last_name
		name
		display_name
		is_agent
		avatar {
		  default_url_pattern
		  url_pattern
		  base_gravatar_url
		}
		online
		online_for_chat
		last_seen
		agent_data {
		  extension_number
		  is_voice_enabled
		  available_status
		  agent_calls_enabled
		  outbound_calls_enabled
		}

	}
}
';

$client = new GraphQL\Client('http://deskpro-dev.com');

$data = $client->execute($query, [
    'id' => 1
]);
print_r($data['content_get_news']);